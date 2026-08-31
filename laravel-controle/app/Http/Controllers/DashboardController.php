<?php

namespace App\Http\Controllers;

use App\Models\Calibracao;
use App\Models\Equipamento;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $equipamentos = Equipamento::ativos()
            ->with(['usuarioResponsavel', 'centroCusto'])
            ->get();

        $cards = [
            'total' => $equipamentos->count(),
            'em_dia' => $equipamentos->where('status_calibragem_key', 'em-dia')->count(),
            'proxima' => $equipamentos->where('status_calibragem_key', 'atencao')->count(),
            'critica' => $equipamentos->where('status_calibragem_key', 'critica')->count(),
            'vencida' => $equipamentos->where('status_calibragem_key', 'vencida')->count(),
            'sem_calibracao' => $equipamentos->where('status_calibragem_key', 'sem-calibracao')->count(),
        ];

        $atencao = $equipamentos->filter(function ($equipamento) {
            return in_array($equipamento->status_calibragem_key, ['atencao', 'critica', 'vencida']);
        })->sortBy(function ($equipamento) {
            $prioridade = [
                'vencida' => 0,
                'critica' => 1,
                'atencao' => 2,
            ];

            return [
                $prioridade[$equipamento->status_calibragem_key] ?? 9,
                $equipamento->dias_restantes ?? 9999,
                $equipamento->nome,
            ];
        });

        $ultimasCalibracoes = Calibracao::with(['equipamento.usuarioResponsavel', 'equipamento.centroCusto', 'usuario'])
            ->whereHas('equipamento', fn ($query) => $query->ativos())
            ->orderByDesc('data_calibragem')
            ->limit(5)
            ->get();

        return view('dashboard', compact('cards', 'atencao', 'ultimasCalibracoes'));
    }
}
