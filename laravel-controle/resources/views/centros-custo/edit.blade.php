<x-app-layout>
    <div class="pt-4 lg:pt-0">
        <div class="mx-auto w-full max-w-xl lg:max-w-4xl">
            <section class="app-card p-4 sm:p-5 lg:p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] app-accent-text">Administração</p>
                <h1 class="mt-1 text-2xl font-bold">Editar {{ $centroCusto->codigo }}</h1>

                <form action="{{ route('centros-custo.update', $centroCusto) }}" method="POST" class="mt-6 grid gap-5 lg:grid-cols-2">
                    @csrf
                    @method('PUT')
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
