<section class="space-y-6">
    <header>
        <h2 class="text-lg font-semibold text-white">Informações do perfil</h2>
        <p class="text-sm text-slate-400">Atualize seu login e nome de exibição.</p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="username" value="Login" class="text-slate-200" />
            <x-text-input id="username" name="username" type="text" class="mt-2 app-input" :value="old('username', $user->username)" required autofocus autocomplete="username" />
            <x-input-error class="mt-2 text-rose-400" :messages="$errors->get('username')" />
        </div>

        <div>
            <x-input-label for="name" value="Nome de exibição" class="text-slate-200" />
            <x-text-input id="name" name="name" type="text" class="mt-2 app-input" :value="old('name', $user->name)" required autocomplete="name" />
            <x-input-error class="mt-2 text-rose-400" :messages="$errors->get('name')" />
        </div>

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
            <x-primary-button class="rounded-3xl px-6 py-4">Salvar</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-slate-400">Salvo.</p>
            @endif
        </div>
    </form>
</section>
