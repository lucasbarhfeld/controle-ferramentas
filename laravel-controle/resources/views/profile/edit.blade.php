<x-app-layout>
    <div class="pt-6">
        <div class="mx-auto w-full max-w-xl lg:max-w-3xl space-y-6">
            <section class="app-card p-4 sm:p-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Perfil</p>
                        <h1 class="mt-2 text-3xl font-bold text-white">Minha conta</h1>
                    </div>
                </div>
            </section>

            <section class="app-card p-4 sm:p-6">
                <div class="space-y-6">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </section>

            <section class="app-card p-4 sm:p-6">
                <div class="space-y-6">
                    @include('profile.partials.update-password-form')
                </div>
            </section>

            <section class="app-card p-4 sm:p-6">
                <div class="space-y-6">
                    @include('profile.partials.delete-user-form')
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
