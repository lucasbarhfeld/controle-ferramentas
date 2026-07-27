<x-guest-layout>
    <section class="space-y-6 app-section">
        <div>
            <p class="text-sm uppercase tracking-[0.3em] text-slate-500">Acesso</p>
            <h1 class="mt-3 text-3xl font-bold text-white">Verificação</h1>
            <p class="mt-3 text-sm text-slate-400">Seu acesso já pode ser gerenciado por um administrador.</p>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-primary-button class="w-full rounded-3xl px-6 py-4">Sair</x-primary-button>
        </form>
    </section>
</x-guest-layout>
