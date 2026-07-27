<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::orderBy('name')->get();

        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'username' => Str::lower(trim((string) $request->input('username'))),
        ]);

        $dados = $request->validate([
            'username' => ['required', 'string', 'max:50', 'regex:/^[a-z0-9._-]+$/', 'unique:users,username'],
            'name' => ['required', 'string', 'max:255', 'unique:users,name'],
            'perfil' => ['required', 'string', 'in:admin,usuario'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'username' => $dados['username'],
            'name' => $dados['name'],
            'email' => 'usuario-' . uniqid() . '@controle.local',
            'perfil' => $dados['perfil'],
            'password' => Hash::make($dados['password']),
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuário criado com sucesso.');
    }

    public function edit(User $usuario)
    {
        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, User $usuario)
    {
        $request->merge([
            'username' => Str::lower(trim((string) $request->input('username'))),
        ]);

        $dados = $request->validate([
            'username' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-z0-9._-]+$/',
                Rule::unique('users', 'username')->ignore($usuario->id),
            ],
            'name' => ['required', 'string', 'max:255', 'unique:users,name,' . $usuario->id],
            'perfil' => ['required', 'string', 'in:admin,usuario'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if (empty($dados['password'])) {
            unset($dados['password']);
        } else {
            $dados['password'] = Hash::make($dados['password']);
        }

        $usuario->update($dados);

        return redirect()->route('usuarios.index')->with('success', 'Usuário atualizado com sucesso.');
    }

    public function destroy(User $usuario)
    {
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuário excluído com sucesso.');
    }
}
