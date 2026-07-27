<?php

namespace App\Http\Controllers;

use App\Models\CentroCusto;
use App\Models\User;
use Illuminate\View\View;

class CadastroController extends Controller
{
    public function index(): View
    {
        $resumo = [
            'usuarios' => User::count(),
            'administradores' => User::where('perfil', 'admin')->count(),
            'centros_custo' => CentroCusto::count(),
            'centros_ativos' => CentroCusto::where('ativo', true)->count(),
        ];

        return view('cadastros.index', compact('resumo'));
    }
}
