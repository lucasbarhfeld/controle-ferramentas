<x-guest-layout>
    <section class="space-y-6 app-section">
        <div>
            <p class="text-sm uppercase tracking-[0.3em] text-slate-500">Acesso</p>
            <h1 class="mt-3 text-3xl font-bold text-white">Redefinir senha</h1>
            <p class="mt-3 text-sm text-slate-400">A senha deve ser redefinida por um administrador.</p>
        </div>

        <a href="{{ route('login') }}" class="app-button app-button-primary w-full">Voltar ao login</a>
    </section>
</x-guest-layout>
