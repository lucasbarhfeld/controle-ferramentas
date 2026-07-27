<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'username' => Str::lower(trim((string) $request->input('username'))),
        ]);

        $request->validate([
            'username' => ['required', 'string', 'max:50', 'regex:/^[a-z0-9._-]+$/', Rule::unique('users', 'username')],
            'name' => ['required', 'string', 'max:255', 'unique:users,name'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => 'usuario-' . uniqid() . '@controle.local',
            'password' => Hash::make($request->password),
            'perfil' => 'usuario',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
