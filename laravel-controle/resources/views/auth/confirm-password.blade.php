<x-guest-layout>
    <section class="space-y-6 app-section">
        <div>
            <p class="text-sm uppercase tracking-[0.3em] text-slate-500">Área segura</p>
            <h1 class="mt-3 text-3xl font-bold text-white">Confirme sua senha</h1>
            <p class="mt-3 text-sm text-slate-400">Por segurança, confirme sua senha antes de continuar.</p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
            @csrf

            <div>
                <x-input-label for="password" :value="__('Password')" class="text-slate-200" />
                <x-text-input id="password" class="mt-2 app-input"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-rose-400" />
            </div>

            <div class="flex justify-end">
                <x-primary-button class="rounded-3xl px-6 py-4">{{ __('Confirm') }}</x-primary-button>
            </div>
        </form>
    </section>
</x-guest-layout>
