<x-guest-layout>
    <section class="app-card overflow-hidden lg:grid lg:grid-cols-[minmax(0,0.95fr)_minmax(420px,0.75fr)]">
        <div class="hidden border-r border-[var(--app-border)] p-8 lg:flex lg:flex-col lg:justify-center">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] app-accent-text">Controle de ferramentas</p>
                <h1 class="mt-4 text-4xl font-black leading-tight">Gestão de calibrações Lippel</h1>
                <p class="mt-4 max-w-md text-base leading-7 app-muted">Acompanhe status, responsáveis, histórico e auditorias das ferramentas em uma interface preparada para celular e PC.</p>
            </div>
        </div>

        <div class="space-y-6 p-4 sm:p-6 lg:p-8">
            <div>
                <h1 class="text-3xl font-bold text-white lg:text-4xl">Entrar</h1>
            </div>

            <x-auth-session-status class="rounded-3xl bg-emerald-500/10 p-4 text-sm text-emerald-200" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <x-input-label for="username" value="Login" class="text-slate-200" />
                    <x-text-input id="username" class="mt-2 app-input" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('username')" class="mt-2 text-rose-400" />
                </div>

                <div>
                    <x-input-label for="password" value="Senha" class="text-slate-200" />
                    <x-text-input id="password" class="mt-2 app-input"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-rose-400" />
                </div>

                <div class="flex items-center gap-3 text-sm text-slate-400">
                    <label for="remember_me" class="inline-flex items-center gap-2">
                        <input id="remember_me" type="checkbox" class="rounded border-slate-700 bg-slate-900 text-sky-500 shadow-sm focus:ring-sky-500" name="remember">
                        <span>Lembrar acesso</span>
                    </label>
                </div>

                <x-primary-button class="w-full rounded-3xl px-6 py-4">Entrar</x-primary-button>
            </form>
        </div>
    </section>
</x-guest-layout>
