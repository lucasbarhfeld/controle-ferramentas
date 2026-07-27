<x-app-layout>
    <div class="pt-4 lg:pt-0">
        <div class="mx-auto w-full max-w-xl lg:max-w-4xl">
            <section class="app-card p-4 sm:p-5 lg:p-6">
                <a href="{{ route('centros-custo.index') }}" class="inline-flex items-center gap-2 text-[11px] font-black uppercase tracking-[0.16em] app-accent-text">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6" />
                    </svg>
                    Centros de custo
                </a>
                <h1 class="mt-3 text-2xl font-bold">Cadastrar centro de custo</h1>

                <form action="{{ route('centros-custo.store') }}" method="POST" class="mt-6 grid gap-5 lg:grid-cols-2">
                    @csrf
                    @include('centros-custo.partials.fields')
                    <div class="app-form-actions lg:col-span-2">
                        <button type="submit" class="app-button app-button-primary">Salvar</button>
                        <a href="{{ route('centros-custo.index') }}" class="app-button app-button-secondary">Cancelar</a>
                    </div>
                </form>
            </section>
        </div>
    </div>
</x-app-layout>
