<x-app-layout>
    <div class="pt-2 lg:pt-0">
        <div class="mx-auto w-full lg:max-w-4xl">
            <section class="py-3">
                <p class="text-[11px] font-semibold uppercase tracking-[0.3em] text-sky-300/80">Usuários</p>
                <h1 class="mt-2 text-2xl font-black leading-tight text-white">Novo usuário</h1>
            </section>

            <section class="app-card p-4 lg:p-6">
                <form action="{{ route('usuarios.store') }}" method="POST" class="space-y-4 lg:grid lg:grid-cols-2 lg:gap-4 lg:space-y-0">
                    @csrf

                    <div>
                        <label class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-400">Login</label>
                        <input type="text" name="username" value="{{ old('username') }}" class="mt-2 app-input" autocomplete="username">
                        @error('username')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-400">Nome de exibição</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="mt-2 app-input" autocomplete="name">
                        @error('name')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-400">Perfil</label>
                        <select name="perfil" class="mt-2 app-select">
                            <option value="admin" @selected(old('perfil') === 'admin')>Admin</option>
                            <option value="usuario" @selected(old('perfil', 'usuario') === 'usuario')>Usuário</option>
                        </select>
                        @error('perfil')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-400">Senha</label>
                        <input type="password" name="password" class="mt-2 app-input" autocomplete="new-password">
                        @error('password')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-400">Confirmar senha</label>
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
