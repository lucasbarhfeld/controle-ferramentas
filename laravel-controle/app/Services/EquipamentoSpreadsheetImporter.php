<?php

namespace App\Services;

use App\Models\CentroCusto;
use App\Models\Equipamento;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Throwable;

class EquipamentoSpreadsheetImporter
{
    private const MAX_ROWS = 5000;

    private const HEADER_ALIASES = [
        'nome' => ['descricao', 'nome', 'ferramenta', 'equipamento'],
        'fabricante' => ['fabricante', 'marca'],
        'faixa_uso' => ['faixa de uso', 'faixa uso'],
        'responsavel' => ['responsavel'],
        'localizacao' => ['setor', 'localizacao'],
        'ultima_calibragem' => ['data calibracao', 'data de calibracao', 'ultima calibracao'],
        'periodo_anos' => ['periodo anos', 'periodo ano'],
        'periodo_dias' => ['periodo dias', 'periodo dia'],
    ];

    public function import(string $path): array
    {
        $reader = IOFactory::createReaderForFile($path);
        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($path);

        try {
            $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        } finally {
            $spreadsheet->disconnectWorksheets();
        }

        [$headerRow, $columns] = $this->findHeader($rows);

        if ($headerRow === null || ! isset($columns['nome'])) {
            throw ValidationException::withMessages([
                'arquivo' => 'Não encontrei a coluna Descrição, Nome, Ferramenta ou Equipamento.',
            ]);
        }

        if (count($rows) - $headerRow > self::MAX_ROWS) {
            throw ValidationException::withMessages([
                'arquivo' => 'A planilha ultrapassa o limite de ' . self::MAX_ROWS . ' linhas.',
            ]);
        }

        $usuarios = User::query()
            ->get(['id', 'name'])
            ->keyBy(fn (User $user) => $this->normalize($user->name));

        $centrosCusto = collect();

        CentroCusto::query()->get()->each(function (CentroCusto $centroCusto) use ($centrosCusto): void {
            $centrosCusto->put($this->normalize($centroCusto->codigo), $centroCusto);

            if ($centroCusto->nome) {
                $centrosCusto->put($this->normalize($centroCusto->nome), $centroCusto);
            }
        });

        $avisos = [];
        $avisosOmitidos = 0;
        $responsaveisDesconhecidos = [];
        $importados = 0;
        $ignorados = 0;

        DB::transaction(function () use (
            $rows,
            $headerRow,
            $columns,
            $usuarios,
            $centrosCusto,
            &$avisos,
            &$avisosOmitidos,
            &$responsaveisDesconhecidos,
            &$importados,
            &$ignorados,
        ): void {
            foreach ($rows as $rowNumber => $row) {
                if ($rowNumber <= $headerRow || $this->rowIsEmpty($row)) {
                    continue;
                }

                $nome = $this->text($row[$columns['nome']] ?? null);

                if ($nome === null) {
                    $ignorados++;
                    $this->addWarning($avisos, $avisosOmitidos, "Linha {$rowNumber}: descrição vazia.");
                    continue;
                }

                $responsavelNome = isset($columns['responsavel'])
                    ? $this->text($row[$columns['responsavel']] ?? null)
                    : null;
                [$vinculo, $responsavelEncontrado] = $this->resolveVinculo(
                    $responsavelNome,
                    $usuarios,
                    $centrosCusto,
                );

                if ($responsavelNome && ! $responsavelEncontrado) {
                    $responsaveisDesconhecidos[$responsavelNome] = true;
                }

                $ultimaCalibragem = null;

                if (isset($columns['ultima_calibragem'])) {
                    $dateValue = $row[$columns['ultima_calibragem']] ?? null;
                    $ultimaCalibragem = $this->date($dateValue);

                    if ($this->text($dateValue) !== null && $ultimaCalibragem === null) {
                        $this->addWarning($avisos, $avisosOmitidos, "Linha {$rowNumber}: data de calibração inválida.");
                    }
                }

                Equipamento::create([
                    'codigo' => $this->newCode(),
                    'nome' => $nome,
                    'fabricante' => $this->columnText($row, $columns, 'fabricante'),
                    'modelo' => null,
                    'localizacao' => $this->columnText($row, $columns, 'localizacao'),
                    'faixa_uso' => $this->columnText($row, $columns, 'faixa_uso'),
                    'status' => 'Ativo',
                    'tipo_vinculacao' => $vinculo['tipo_vinculacao'],
                    'usuario_responsavel_id' => $vinculo['usuario_responsavel_id'],
                    'centro_custo_id' => $vinculo['centro_custo_id'],
                    'ultima_calibragem' => $ultimaCalibragem,
                    'periodo_calibragem_dias' => $this->periodDays($row, $columns),
                ]);

                $importados++;
            }
        });

        foreach (array_keys($responsaveisDesconhecidos) as $responsavelNome) {
            $this->addWarning(
                $avisos,
                $avisosOmitidos,
                "Vinculação não reconhecida: {$responsavelNome}. A ferramenta ficou sem responsável.",
            );
        }

        if ($avisosOmitidos > 0) {
            $avisos[] = "Outros {$avisosOmitidos} aviso(s) foram omitidos.";
        }

        return [
            'importados' => $importados,
            'ignorados' => $ignorados,
            'avisos' => $avisos,
        ];
    }

    private function findHeader(array $rows): array
    {
        foreach (array_slice($rows, 0, 30, true) as $rowNumber => $row) {
            $columns = [];

            foreach ($row as $column => $value) {
                $header = $this->normalize($value);

                foreach (self::HEADER_ALIASES as $field => $aliases) {
                    if (in_array($header, $aliases, true)) {
                        $columns[$field] = $column;
                        break;
                    }
                }
            }

            if (isset($columns['nome']) && count($columns) >= 2) {
                return [$rowNumber, $columns];
            }
        }

        return [null, []];
    }

    private function columnText(array $row, array $columns, string $field): ?string
    {
        return isset($columns[$field]) ? $this->text($row[$columns[$field]] ?? null) : null;
    }

    private function periodDays(array $row, array $columns): int
    {
        if (isset($columns['periodo_dias'])) {
            $days = $this->number($row[$columns['periodo_dias']] ?? null);

            if ($days !== null && $days > 0) {
                return max(1, (int) round($days));
            }
        }

        if (isset($columns['periodo_anos'])) {
            $years = $this->number($row[$columns['periodo_anos']] ?? null);

            if ($years !== null && $years > 0) {
                return max(1, (int) round($years * 365));
            }
        }

        return 360;
    }

    private function resolveVinculo(?string $nome, $usuarios, $centrosCusto): array
    {
        $semResponsavel = [
            'tipo_vinculacao' => Equipamento::VINCULO_SEM_RESPONSAVEL,
            'usuario_responsavel_id' => null,
            'centro_custo_id' => null,
        ];

        if ($nome === null) {
            return [$semResponsavel, true];
        }

        $normalizado = $this->normalize($nome);
        $compacto = preg_replace('/[^a-z0-9]+/', '', Str::ascii(Str::lower($nome)));

        if (in_array($normalizado, ['armario coletivo', 'armario de uso coletivo'], true)) {
            return [[
                'tipo_vinculacao' => Equipamento::VINCULO_ARMARIO_COLETIVO,
                'usuario_responsavel_id' => null,
                'centro_custo_id' => null,
            ], true];
        }

        $centroCusto = $centrosCusto->get($normalizado);

        if (! $centroCusto && preg_match('/^(?:cc|centrodecusto)(\d+)$/', $compacto, $matches)) {
            $codigo = 'CC ' . $matches[1];
            $centroCusto = CentroCusto::firstOrCreate(
                ['codigo' => $codigo],
                [
                    'nome' => null,
                    'descricao' => 'Criado automaticamente durante a importação de ferramentas.',
                    'ativo' => true,
                ],
            );

            $centrosCusto->put($this->normalize($centroCusto->codigo), $centroCusto);
        }

        if ($centroCusto) {
            return [[
                'tipo_vinculacao' => Equipamento::VINCULO_CENTRO_CUSTO,
                'usuario_responsavel_id' => null,
                'centro_custo_id' => $centroCusto->id,
            ], true];
        }

        $usuario = $usuarios->get($normalizado);

        if ($usuario) {
            return [[
                'tipo_vinculacao' => Equipamento::VINCULO_USUARIO,
                'usuario_responsavel_id' => $usuario->id,
                'centro_custo_id' => null,
            ], true];
        }

        return [$semResponsavel, false];
    }

    private function date(mixed $value): ?Carbon
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            try {
                return Carbon::instance(ExcelDate::excelToDateTimeObject((float) $value))->startOfDay();
            } catch (Throwable) {
                return null;
            }
        }

        $value = trim((string) $value);

        foreach (['d/m/Y', 'd/m/y', 'Y-m-d', 'd-m-Y'] as $format) {
            try {
                $date = Carbon::createFromFormat('!' . $format, $value);

                if ($date !== false && $date->format($format) === $value) {
                    return $date->startOfDay();
                }
            } catch (Throwable) {
                // Try the next supported date format.
            }
        }

        return null;
    }

    private function number(mixed $value): ?float
    {
        if (is_int($value) || is_float($value)) {
            return (float) $value;
        }

        if (! is_string($value)) {
            return null;
        }

        if (! preg_match('/-?\d+(?:[.,]\d+)?/', $value, $match)) {
            return null;
        }

        return (float) str_replace(',', '.', $match[0]);
    }

    private function text(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = preg_replace('/\s+/u', ' ', trim((string) $value));

        return $value === '' || $value === '-' ? null : $value;
    }

    private function normalize(mixed $value): string
    {
        return Str::of((string) $value)
            ->lower()
            ->ascii()
            ->replaceMatches('/[^a-z0-9]+/', ' ')
            ->squish()
            ->toString();
    }

    private function rowIsEmpty(array $row): bool
    {
        foreach ($row as $value) {
            if ($this->text($value) !== null) {
                return false;
            }
        }

        return true;
    }

    private function newCode(): string
    {
        do {
            $code = 'FERR-' . Str::upper(Str::random(8));
        } while (Equipamento::where('codigo', $code)->exists());

        return $code;
    }

    private function addWarning(array &$warnings, int &$omitted, string $warning): void
    {
        if (count($warnings) < 20) {
            $warnings[] = $warning;
            return;
        }

        $omitted++;
    }
}
