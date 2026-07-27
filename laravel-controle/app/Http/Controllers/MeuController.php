<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class MeuController extends Controller
{
    public function index(Request $request): View
    {
        $usuario = $request->user();
        $equipamentos = $usuario->equipamentosResponsaveis()->with('calibracoes')->get();
        $calibracoes = $usuario->calibracoes()->with('equipamento')->orderByDesc('data_calibragem')->get();

        return view('me.index', compact('usuario', 'equipamentos', 'calibracoes'));
    }
}
