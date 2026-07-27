<?php

namespace App\Http\Controllers;

use App\Services\EquipamentoSpreadsheetImporter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class EquipamentoImportController extends Controller
{
    public function __invoke(Request $request, EquipamentoSpreadsheetImporter $importer): RedirectResponse
    {
        $request->validate([
            'arquivo' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
        ], [
            'arquivo.required' => 'Selecione uma planilha para importar.',
            'arquivo.mimes' => 'Envie um arquivo XLSX, XLS ou CSV.',
            'arquivo.max' => 'A planilha deve ter no máximo 10 MB.',
        ]);

        try {
            $resultado = $importer->import($request->file('arquivo')->getRealPath());
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors([
                'arquivo' => 'Não foi possível ler a planilha. Confira o formato e tente novamente.',
            ]);
        }

        $mensagem = $resultado['importados'] . ' ferramenta(s) importada(s).';

        if ($resultado['ignorados'] > 0) {
            $mensagem .= ' ' . $resultado['ignorados'] . ' linha(s) ignorada(s).';
        }

        return redirect()
            ->route('equipamentos.index')
            ->with('success', $mensagem)
            ->with('import_warnings', $resultado['avisos']);
    }
}
