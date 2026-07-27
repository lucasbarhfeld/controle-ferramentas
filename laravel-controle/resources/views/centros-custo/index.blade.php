<x-app-layout>
    <div class="pt-2 lg:pt-0">
        <div class="mx-auto w-full lg:max-w-7xl">
            @if (session('success'))
                <div class="mb-3 app-alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="mb-3 rounded-2xl border border-rose-500/40 bg-rose-500/10 px-4 py-3 text-sm text-rose-300">
                    {{ session('error') }}
                </div>
            @endif

            <section class="py-3 lg:app-card lg:p-6">
                <div class="flex items-end justify-between gap-4">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.3em] app-accent-text">Administração</p>
                        <h1 class="mt-2 text-2xl font-black leading-tight">Centros de custo</h1>
                        <p class="mt-1 text-sm app-muted">{{ $centrosCusto->count() }} centro(s) cadastrado(s)</p>
                    </div>
                    <a href="{{ route('centros-custo.create') }}" class="app-button app-button-primary shrink-0">Cadastrar</a>
                </div>

                <x-admin-tabs active="centros-custo" />
            </section>

            <section class="mt-4 grid gap-3 lg:grid-cols-2 xl:grid-cols-3">
                @forelse ($centrosCusto as $centroCusto)
                    <div class="app-list-card app-card-hover">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex min-w-0 items-start gap-3">
                                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-emerald-500/15 text-emerald-300">
                                    <x-vinculo-icon type="centro_custo" class="h-5 w-5" />
                                </span>
                                <div class="min-w-0">
                                    <p class="truncate text-base font-black">{{ $centroCusto->codigo }}</p>
                                    <p class="mt-1 truncate text-xs app-muted">{{ $centroCusto->nome ?: 'Sem descrição curta' }}</p>
                                    <p class="mt-1 text-xs app-muted">{{ $centroCusto->equipamentos_count }} ferramenta(s)</p>
                                </div>
                            </div>
                            <span class="app-badge {{ $centroCusto->ativo ? 'text-emerald-300' : 'text-slate-400' }}">
                                {{ $centroCusto->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>

                        @if ($centroCusto->descricao)
                            <p class="mt-3 line-clamp-2 text-xs leading-5 app-muted">{{ $centroCusto->descricao }}</p>
                        @endif

                        <div class="mt-4 grid grid-cols-2 gap-2">
                            <a href="{{ route('centros-custo.edit', $centroCusto) }}" class="app-button app-button-secondary">Editar</a>
                            <button
                                type="button"
                                class="app-button app-button-danger"
                                x-data
                                x-on:click="$dispatch('open-modal', 'excluir-centro-{{ $centroCusto->id }}')"
                            >
                                Excluir
                            </button>
                        </div>
                    </div>

                    <x-modal name="excluir-centro-{{ $centroCusto->id }}" focusable maxWidth="sm">
                        <div class="p-5 sm:p-6">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-rose-400">Excluir centro de custo</p>
                            <h2 class="mt-2 text-xl font-bold">Excluir {{ $centroCusto->codigo }}?</h2>
                            <p class="mt-2 text-sm leading-6 app-muted">
                                A exclusão só será permitida quando nenhuma ferramenta estiver vinculada a este centro.
                            </p>
                            <div class="mt-6 grid grid-cols-2 gap-3">
                                <button type="button" class="app-button app-button-secondary" x-on:click="$dispatch('close')">Cancelar</button>
                                <form method="POST" action="{{ route('centros-custo.destroy', $centroCusto) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="app-button app-button-danger w-full">Sim, excluir</button>
                                </form>
                            </div>
                        </div>
                    </x-modal>
                @empty
                    <div class="app-alert-empty lg:col-span-2 xl:col-span-3">
                        Nenhum centro de custo cadastrado. Cadastre o primeiro para vinculá-lo às ferramentas.
                    </div>
                @endforelse
            </section>
        </div>
    </div>
</x-app-layout>
