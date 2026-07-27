<section class="space-y-6">
    <header>
        <h2 class="text-lg font-semibold text-white">Senha</h2>
        <p class="text-sm text-slate-400">Atualize sua senha de acesso para manter sua conta segura.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" class="text-slate-200" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-2 app-input" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-rose-400" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" class="text-slate-200" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-2 app-input" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-rose-400" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="text-slate-200" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-2 app-input" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-rose-400" />
        </div>

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
            <x-primary-button class="rounded-3xl px-6 py-4">{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-slate-400">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
