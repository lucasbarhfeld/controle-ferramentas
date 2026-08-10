<x-app-layout>
    <div class="pt-3 lg:pt-0">
        <div class="mx-auto w-full max-w-xl lg:max-w-7xl">
            <section class="app-card p-3 sm:p-4 lg:p-6">
                <div class="flex items-center justify-between gap-4">
                    <div class="min-w-0">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.24em] app-muted">Calibrações</p>
                        <h1 class="mt-1 text-xl font-black leading-tight">Histórico</h1>
                        <p class="mt-0.5 text-xs app-muted">{{ $calibracoes->count() }} registros encontrados</p>
                    </div>
                    <a href="{{ route('calibracoes.create') }}" class="app-button app-button-primary shrink-0">Nova Calibração</a>
                </div>
            </section>

            <section class="mt-4 grid gap-3 lg:grid-cols-2 xl:grid-cols-3">
                @forelse ($calibracoes as $calibracao)
                    <div class="app-list-card-compact">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-black">{{ $calibracao->equipamento->nome }}</p>
                                <p class="mt-0.5 truncate text-[11px] app-muted">{{ $calibracao->data_calibragem->format('d/m/Y') }}</p>
                                <p class="mt-1 flex min-w-0 items-center gap-1.5 truncate text-[11px] app-muted">
                                    <x-vinculo-icon :type="$calibracao->equipamento->tipo_vinculacao_efetivo" class="h-3.5 w-3.5 shrink-0" />
                                    <span class="truncate">{{ $calibracao->equipamento->vinculo_label }}</span>
                                </p>
                            </div>
                            <div class="shrink-0 text-right">
                                <p class="text-[10px] font-black uppercase tracking-[0.12em] app-muted">Registrado por</p>
                                <p class="mt-0.5 max-w-24 truncate text-xs font-semibold">{{ $calibracao->usuario?->name ?? '-' }}</p>
                            </div>
                        </div>

                        @if ($calibracao->certificado)
                            <p class="mt-2 truncate text-xs font-semibold app-muted">
                                Certificado: <span class="font-normal">{{ $calibracao->certificado }}</span>
                            </p>
                        @endif

                        @if ($calibracao->observacoes)
                            <p class="mt-2 line-clamp-2 rounded-xl border border-slate-700/40 bg-slate-950/25 px-3 py-2 text-xs leading-5 app-muted">{{ $calibracao->observacoes }}</p>
                        @endif
                    </div>
                @empty
                    <div class="app-alert-empty">
                        Nenhuma calibração registrada ainda.
                    </div>
                @endforelse
            </section>
        </div>
    </div>
</x-app-layout>
