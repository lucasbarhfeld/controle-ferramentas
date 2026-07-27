<section class="space-y-6">
    <header>
        <h2 class="text-lg font-semibold text-white">Excluir conta</h2>
        <p class="text-sm text-slate-400">Ao excluir sua conta, todos os dados associados serão removidos permanentemente.</p>
    </header>

    <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" class="rounded-3xl px-6 py-4">{{ __('Delete Account') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="space-y-6 app-card p-4 sm:p-6 text-slate-100">
            @csrf
            @method('delete')

            <div class="space-y-3">
                <h2 class="text-lg font-semibold text-white">{{ __('Are you sure you want to delete your account?') }}</h2>
                <p class="text-sm text-slate-400">{{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}</p>
            </div>

            <div>
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />
                <x-text-input id="password" name="password" type="password" class="mt-2 block w-full app-input" placeholder="{{ __('Password') }}" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-rose-400" />
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                <x-secondary-button x-on:click="$dispatch('close')" class="app-button-secondary">{{ __('Cancel') }}</x-secondary-button>
                <x-danger-button class="app-button-danger">{{ __('Delete Account') }}</x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
