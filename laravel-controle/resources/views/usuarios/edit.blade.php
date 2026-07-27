<x-app-layout>
    <div class="pt-2 lg:pt-0">
        <div class="mx-auto w-full lg:max-w-4xl">
            <section class="py-3">
                <a href="{{ route('usuarios.index') }}" class="inline-flex items-center gap-2 text-[11px] font-black uppercase tracking-[0.16em] app-accent-text">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6" />
                    </svg>
                    Usuários
                </a>
                <h1 class="mt-3 text-2xl font-black leading-tight">Editar usuário</h1>
            </section>

            <section class="app-card p-4 lg:p-6">
                <form action="{{ route('usuarios.update', $usuario) }}" method="POST" class="space-y-4 lg:grid lg:grid-cols-2 lg:gap-4 lg:space-y-0">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="text-[11px] font-black uppercase tracking-[0.2em] app-muted">Login</label>
                        <input type="text" name="username" value="{{ old('username', $usuario->username) }}" class="mt-2 app-input" autocomplete="username">
                        @error('username')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="text-[11px] font-black uppercase tracking-[0.2em] app-muted">Nome de exibição</label>
                        <input type="text" name="name" value="{{ old('name', $usuario->name) }}" class="mt-2 app-input" autocomplete="name">
                        @error('name')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="text-[11px] font-black uppercase tracking-[0.2em] app-muted">Perfil</label>
                        <select name="perfil" class="mt-2 app-select">
                            <option value="admin" @selected(old('perfil', $usuario->perfil) === 'admin')>Admin</option>
                            <option value="usuario" @selected(old('perfil', $usuario->perfil) === 'usuario')>Usuário</option>
                        </select>
                        @error('perfil')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="text-[11px] font-black uppercase tracking-[0.2em] app-muted">Nova senha</label>
                        <input type="password" name="password" class="mt-2 app-input" autocomplete="new-password">
                        @error('password')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="text-[11px] font-black uppercase tracking-[0.2em] app-muted">Confirmar senha</label>
                        <input type="password" name="password_confirmation" class="mt-2 app-input" autocomplete="new-password">
                    </div>

                    <div class="app-form-actions pt-1 lg:col-span-2">
                        <button type="submit" class="app-button-primary">Salvar</button>
                        <a href="{{ route('usuarios.index') }}" class="app-button-secondary">Cancelar</a>
                    </div>
                </form>
            </section>
        </div>
    </div>
</x-app-layout>
