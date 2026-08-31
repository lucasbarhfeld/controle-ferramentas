<?php

namespace App\Http\Controllers;

use App\Models\Calibracao;
use App\Models\Equipamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CalibracaoController extends Controller
{
    public function create(Request $request)
    {
        $equipamentos = Equipamento::ativos()
            ->with(['usuarioResponsavel', 'centroCusto'])
            ->orderBy('nome')
            ->get();

        $equipamentoSelecionadoId = $request->query('equipamento_id');

        return view('calibracoes.create', compact('equipamentos', 'equipamentoSelecionadoId'));
    }

    public function index()
    {
        $calibracoes = Calibracao::with(['equipamento.usuarioResponsavel', 'equipamento.centroCusto', 'usuario'])
            ->orderByDesc('data_calibragem')
            ->get();

        return view('calibracoes.index', compact('calibracoes'));
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'equipamento_ids' => ['required', 'array', 'min:1'],
            'equipamento_ids.*' => [
                Rule::exists('equipamentos', 'id')
                    ->where(fn ($query) => $query->where('status', Equipamento::STATUS_ATIVO)),
            ],
            'data_calibragem' => ['required', 'date'],
            'certificado' => ['nullable', 'string', 'max:255'],
            'observacoes' => ['nullable', 'string'],
        ]);

        $equipamentos = Equipamento::ativos()
            ->whereIn('id', $dados['equipamento_ids'])
            ->get();

        foreach ($equipamentos as $equipamento) {
            Calibracao::create([
                'equipamento_id' => $equipamento->id,
                'user_id' => Auth::id(),
                'data_calibragem' => $dados['data_calibragem'],
                'certificado' => $dados['certificado'] ?? null,
                'resultado' => 'Registrada',
                'observacoes' => $dados['observacoes'] ?? null,
                'data_registro' => now(),
            ]);

            $equipamento->update([
                'ultima_calibragem' => $dados['data_calibragem'],
            ]);
        }

        $mensagem = $equipamentos->count() === 1
            ? 'Calibração registrada com sucesso.'
            : $equipamentos->count().' calibrações registradas com sucesso.';

        if ($equipamentos->count() === 1) {
            return redirect()
                ->route('equipamentos.show', $equipamentos->first())
                ->with('success', $mensagem);
        }

        return redirect()
            ->route('calibracoes.index')
            ->with('success', $mensagem);
    }
}
