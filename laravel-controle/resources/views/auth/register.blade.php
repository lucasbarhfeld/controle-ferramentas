<x-guest-layout>
    <section class="space-y-6 app-section">
        <div>
            <p class="text-sm uppercase tracking-[0.3em] text-slate-500">Cadastro</p>
            <h1 class="mt-3 text-3xl font-bold text-white">Novo acesso</h1>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <div>
                <x-input-label for="username" value="Login" class="text-slate-200" />
                <x-text-input id="username" class="mt-2 app-input" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('username')" class="mt-2 text-rose-400" />
            </div>

            <div>
                <x-input-label for="name" value="Nome de exibição" class="text-slate-200" />
                <x-text-input id="name" class="mt-2 app-input" type="text" name="name" :value="old('name')" required autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-rose-400" />
            </div>

            <div>
                <x-input-label for="password" value="Senha" class="text-slate-200" />
                <x-text-input id="password" class="mt-2 app-input"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-rose-400" />
            </div>

            <div>
                <x-input-label for="password_confirmation" value="Confirmar senha" class="text-slate-200" />
                <x-text-input id="password_confirmation" class="mt-2 app-input"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-rose-400" />
            </div>

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <a class="text-sm text-slate-400 hover:text-slate-100" href="{{ route('login') }}">
                    Já tenho acesso
                </a>

                <x-primary-button class="rounded-3xl px-6 py-4">Cadastrar</x-primary-button>
            </div>
        </form>
    </section>
</x-guest-layout>
