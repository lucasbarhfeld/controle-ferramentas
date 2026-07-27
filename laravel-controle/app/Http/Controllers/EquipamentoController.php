<?php

namespace App\Http\Controllers;

use App\Models\CentroCusto;
use App\Models\Equipamento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class EquipamentoController extends Controller
{
    public function index()
    {
        $todosEquipamentos = Equipamento::with(['usuarioResponsavel', 'centroCusto'])
            ->orderBy('nome')
            ->get();

        $statusCounts = [
            'todas' => $todosEquipamentos->count(),
            'vencida' => $todosEquipamentos->where('status_calibragem_key', 'vencida')->count(),
            'critica' => $todosEquipamentos->where('status_calibragem_key', 'critica')->count(),
            'proxima' => $todosEquipamentos->where('status_calibragem_key', 'atencao')->count(),
            'em_dia' => $todosEquipamentos->where('status_calibragem_key', 'em-dia')->count(),
        ];

        $equipamentos = $todosEquipamentos;

        $filtroStatus = request('status');

        if ($filtroStatus) {
            $statusMap = [
                'vencida' => 'vencida',
                'critica' => 'critica',
                'proxima' => 'atencao',
                'em_dia' => 'em-dia',
                'sem_calibracao' => 'sem-calibracao',
            ];

            $equipamentos = $equipamentos->filter(function ($equipamento) use ($filtroStatus, $statusMap) {
                return $equipamento->status_calibragem_key === ($statusMap[$filtroStatus] ?? null);
            });
        }

        return view('equipamentos.index', compact('equipamentos', 'statusCounts'));
    }

    public function create()
    {
        $usuarios = $this->usuariosResponsaveis();
        $centrosCusto = CentroCusto::where('ativo', true)->orderBy('codigo')->get();

        return view('equipamentos.create', compact('usuarios', 'centrosCusto'));
    }

    public function export()
    {
        $equipamentos = Equipamento::with(['usuarioResponsavel', 'centroCusto'])
            ->orderBy('nome')
            ->get();

        $filename = 'ferramentas-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($equipamentos) {
            $output = fopen('php://output', 'w');

            fwrite($output, "\xEF\xBB\xBF");

            fputcsv($output, [
                'Nome',
                'Tipo de vinculação',
                'Fabricante',
                'Localização',
                'Faixa de uso',
                'Responsável',
                'Última calibração',
                'Próxima calibração',
                'Período (dias)',
                'Status',
                'Dias restantes',
            ], ';');

            foreach ($equipamentos as $equipamento) {
                fputcsv($output, [
                    $equipamento->nome,
                    $equipamento->vinculo_tipo_label,
                    $equipamento->fabricante,
                    $equipamento->localizacao,
                    $equipamento->faixa_uso,
                    $equipamento->vinculo_label,
                    $equipamento->ultima_calibragem?->format('d/m/Y'),
                    $equipamento->proxima_calibragem?->format('d/m/Y'),
                    $equipamento->periodo_calibragem_dias,
                    $equipamento->status_calibragem,
                    $equipamento->dias_restantes,
                ], ';');
            }

            fclose($output);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'fabricante' => ['nullable', 'string', 'max:255'],
            'localizacao' => ['nullable', 'string', 'max:255'],
            'faixa_uso' => ['nullable', 'string', 'max:255'],
            'foto' => ['nullable', 'image', 'max:5120'],
            'tipo_vinculacao' => ['required', 'in:sem_responsavel,usuario,armario_coletivo,centro_custo'],
            'usuario_responsavel_id' => ['nullable', 'required_if:tipo_vinculacao,usuario', 'exists:users,id'],
            'centro_custo_id' => ['nullable', 'required_if:tipo_vinculacao,centro_custo', 'exists:centros_custo,id'],
            'ultima_calibragem' => ['nullable', 'date'],
            'periodo_calibragem_dias' => ['required', 'integer', 'min:1'],
        ]);

        do {
            $codigo = 'FERR-' . Str::upper(Str::random(8));
        } while (Equipamento::where('codigo', $codigo)->exists());

        $dados['codigo'] = $codigo;
        $dados['modelo'] = null;
        $dados['status'] = 'Ativo';
        $dados = $this->normalizeVinculo($dados);

        if ($request->hasFile('foto')) {
            $dados['foto_path'] = $request->file('foto')->store('equipamentos', 'public');
        }

        Equipamento::create($dados);

        return redirect()
            ->route('equipamentos.index')
            ->with('success', 'Ferramenta cadastrada com sucesso.');
    }

    public function show(Equipamento $equipamento)
    {
        $equipamento->load(['usuarioResponsavel', 'centroCusto', 'calibracoes.usuario']);

        return view('equipamentos.show', compact('equipamento'));
    }

    public function edit(Equipamento $equipamento)
    {
        $usuarios = $this->usuariosResponsaveis();
        $centrosCusto = CentroCusto::query()
            ->where('ativo', true)
            ->when(
                $equipamento->centro_custo_id,
                fn ($query) => $query->orWhere('id', $equipamento->centro_custo_id),
            )
            ->orderBy('codigo')
            ->get();

        return view('equipamentos.edit', compact('equipamento', 'usuarios', 'centrosCusto'));
    }

    public function update(Request $request, Equipamento $equipamento)
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'fabricante' => ['nullable', 'string', 'max:255'],
            'localizacao' => ['nullable', 'string', 'max:255'],
            'faixa_uso' => ['nullable', 'string', 'max:255'],
            'foto' => ['nullable', 'image', 'max:5120'],
            'tipo_vinculacao' => ['required', 'in:sem_responsavel,usuario,armario_coletivo,centro_custo'],
            'usuario_responsavel_id' => ['nullable', 'required_if:tipo_vinculacao,usuario', 'exists:users,id'],
            'centro_custo_id' => ['nullable', 'required_if:tipo_vinculacao,centro_custo', 'exists:centros_custo,id'],
            'ultima_calibragem' => ['nullable', 'date'],
            'periodo_calibragem_dias' => ['required', 'integer', 'min:1'],
        ]);

        if ($request->hasFile('foto')) {
            if ($equipamento->foto_path) {
                Storage::disk('public')->delete($equipamento->foto_path);
            }

            $dados['foto_path'] = $request->file('foto')->store('equipamentos', 'public');
        }

        $equipamento->update($this->normalizeVinculo($dados));

        return redirect()
            ->route('equipamentos.show', $equipamento)
            ->with('success', 'Ferramenta atualizada com sucesso.');
    }

    private function normalizeVinculo(array $dados): array
    {
        if (($dados['tipo_vinculacao'] ?? null) !== Equipamento::VINCULO_USUARIO) {
            $dados['usuario_responsavel_id'] = null;
        }

        if (($dados['tipo_vinculacao'] ?? null) !== Equipamento::VINCULO_CENTRO_CUSTO) {
            $dados['centro_custo_id'] = null;
        }

        return $dados;
    }

    private function usuariosResponsaveis(): Collection
    {
        return User::orderBy('name')
            ->get()
            ->reject(fn (User $usuario) => Equipamento::nomePareceVinculoOrganizacional($usuario->name))
            ->values();
    }

    public function destroy(Equipamento $equipamento)
    {
        $fotoPath = $equipamento->foto_path;
    
        $equipamento->delete();
    
        if ($fotoPath) {
            Storage::disk('public')->delete($fotoPath);
        }
    
        return redirect()
            ->route('equipamentos.index')
            ->with('success', 'Ferramenta excluída com sucesso.');
    }
}
