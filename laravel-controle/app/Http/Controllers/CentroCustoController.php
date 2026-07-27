<?php

namespace App\Http\Controllers;

use App\Models\CentroCusto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CentroCustoController extends Controller
{
    public function index(): View
    {
        $centrosCusto = CentroCusto::withCount('equipamentos')
            ->orderByDesc('ativo')
            ->orderBy('codigo')
            ->get();

        return view('centros-custo.index', compact('centrosCusto'));
    }

    public function create(): View
    {
        return view('centros-custo.create');
    }

    public function store(Request $request): RedirectResponse
    {
        CentroCusto::create($this->validatedData($request));

        return redirect()
            ->route('centros-custo.index')
            ->with('success', 'Centro de custo cadastrado com sucesso.');
    }

    public function edit(CentroCusto $centro_custo): View
    {
        return view('centros-custo.edit', ['centroCusto' => $centro_custo]);
    }

    public function update(Request $request, CentroCusto $centro_custo): RedirectResponse
    {
        $centro_custo->update($this->validatedData($request, $centro_custo));

        return redirect()
            ->route('centros-custo.index')
            ->with('success', 'Centro de custo atualizado com sucesso.');
    }

    public function destroy(CentroCusto $centro_custo): RedirectResponse
    {
        if ($centro_custo->equipamentos()->exists()) {
            return redirect()
                ->route('centros-custo.index')
                ->with('error', 'Este centro de custo está vinculado a ferramentas e não pode ser excluído. Desative-o ou altere os vínculos primeiro.');
        }

        $centro_custo->delete();

        return redirect()
            ->route('centros-custo.index')
            ->with('success', 'Centro de custo excluído com sucesso.');
    }

    private function validatedData(Request $request, ?CentroCusto $centroCusto = null): array
    {
        $request->merge([
            'codigo' => trim((string) $request->input('codigo')),
            'nome' => trim((string) $request->input('nome')) ?: null,
        ]);

        $codigoRule = Rule::unique('centros_custo', 'codigo');

        if ($centroCusto) {
            $codigoRule->ignore($centroCusto->id);
        }

        $dados = $request->validate([
            'codigo' => [
                'required',
                'string',
                'max:50',
                $codigoRule,
            ],
            'nome' => ['nullable', 'string', 'max:255'],
            'descricao' => ['nullable', 'string', 'max:1000'],
            'ativo' => ['nullable', 'boolean'],
        ]);

        $dados['ativo'] = $request->boolean('ativo');

        return $dados;
    }
}
