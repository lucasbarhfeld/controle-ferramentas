<x-app-layout>

    <div class="pt-2 lg:pt-0">
        <div class="mx-auto w-full lg:max-w-7xl">
            <section class="py-3 lg:rounded-[28px] lg:border lg:border-[var(--app-border)] lg:bg-[var(--app-surface)] lg:p-6 lg:shadow-xl">
                <p class="text-[11px] font-semibold uppercase tracking-[0.3em] app-accent-text">{{ now()->translatedFormat('l, d M Y') }}</p>
                <div class="mt-2 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <a href="{{ route('calibracoes.create') }}" class="app-button app-button-primary hidden lg:inline-flex">Nova calibração</a>
                </div>

                <div class="mt-5 grid grid-cols-2 gap-3 lg:grid-cols-4 lg:gap-4">
                    <a href="{{ route('equipamentos.index', ['status' => 'vencida']) }}" class="app-status-card status-vencida">
                        <div class="flex items-center gap-3">
                            <div class="app-status-icon status-vencida">x</div>
                            <div>
                                <p class="app-status-value status-vencida text-2xl font-black lg:text-3xl">{{ $cards['vencida'] }}</p>
                                <p class="text-[10px] font-black uppercase tracking-[0.12em] app-muted">Vencido</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('equipamentos.index', ['status' => 'critica']) }}" class="app-status-card status-critica">
                        <div class="flex items-center gap-3">
                            <div class="app-status-icon status-critica">!</div>
                            <div>
                                <p class="app-status-value status-critica text-2xl font-black lg:text-3xl">{{ $cards['critica'] }}</p>
                                <p class="text-[10px] font-black uppercase tracking-[0.12em] app-muted">Crítico</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('equipamentos.index', ['status' => 'proxima']) }}" class="app-status-card status-atencao">
                        <div class="flex items-center gap-3">
                            <div class="app-status-icon status-atencao">!</div>
                            <div>
                                <p class="app-status-value status-atencao text-2xl font-black lg:text-3xl">{{ $cards['proxima'] }}</p>
                                <p class="text-[10px] font-black uppercase tracking-[0.12em] app-muted">Atenção</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('equipamentos.index', ['status' => 'em_dia']) }}" class="app-status-card status-em-dia">
                        <div class="flex items-center gap-3">
                            <div class="app-status-icon status-em-dia">✓</div>
                            <div>
                                <p class="app-status-value status-em-dia text-2xl font-black lg:text-3xl">{{ $cards['em_dia'] }}</p>
                                <p class="text-[10px] font-black uppercase tracking-[0.12em] app-muted">Calibrado</p>
                            </div>
                        </div>
                    </a>
                </div>
            </section>

            <div class="mt-3 grid gap-5 lg:mt-6 lg:grid-cols-[minmax(0,1.15fr)_minmax(360px,0.85fr)]">
            <section class="lg:app-card lg:p-5">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-base font-black uppercase tracking-[0.12em]">Requerem atenção</h2>
                    <a href="{{ route('equipamentos.index') }}" class="text-[11px] font-black uppercase tracking-[0.12em] app-accent-text">Ver todos</a>
                </div>

                <div class="mt-3 space-y-3">
                    @forelse ($atencao->take(5) as $equipamento)
                        <a href="{{ route('equipamentos.show', $equipamento) }}" class="app-list-card app-card-hover block text-left">
                            <div class="flex items-center justify-between gap-3">
                                <div class="min-w-0 app-calibration-marker status-{{ $equipamento->status_calibragem_key }}">
                                    <p class="truncate text-base font-black">{{ $equipamento->nome }}</p>
                                    <p class="mt-0.5 flex min-w-0 items-center gap-1.5 truncate text-xs app-muted">
                                        <x-vinculo-icon :type="$equipamento->tipo_vinculacao_efetivo" class="h-3.5 w-3.5 shrink-0" />
                                        <span class="truncate">{{ $equipamento->vinculo_label }}</span>
                                    </p>
                                </div>
                                <div class="shrink-0 text-right">
                                    <p class="app-calibration-days status-{{ $equipamento->status_calibragem_key }} text-base font-black">
                                        {{ $equipamento->dias_restantes < 0 ? $equipamento->dias_restantes . 'd' : '' . $equipamento->dias_restantes . 'd' }}
                                    </p>
                                    <p class="app-calibration-days status-{{ $equipamento->status_calibragem_key }} text-[10px] font-black uppercase tracking-[0.1em]">{{ $equipamento->status_calibragem }}</p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="app-alert-empty">Nenhuma ferramenta precisa de atenção agora.</div>
                    @endforelse
                </div>

                <a href="{{ route('calibracoes.create') }}" class="app-button app-button-primary mt-4 w-full">Nova calibração</a>
            </section>

            <section class="mt-5 lg:mt-0 lg:app-card lg:p-5">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-base font-black uppercase tracking-[0.12em]">Últimas calibrações</h2>
                    <a href="{{ route('calibracoes.index') }}" class="text-[11px] font-black uppercase tracking-[0.12em] app-accent-text">Histórico</a>
                </div>

                <div class="mt-3 space-y-3">
                    @forelse ($ultimasCalibracoes as $calibracao)
                        <div class="app-list-card">
                            <div class="min-w-0">
                                <p class="inline-block truncate border-b border-slate-600 pb-0.5 font-black">{{ $calibracao->equipamento->nome }}</p>
                                <p class="mt-1 flex min-w-0 items-center gap-1.5 truncate text-xs app-muted">
                                    <x-vinculo-icon :type="$calibracao->equipamento->tipo_vinculacao_efetivo" class="h-3.5 w-3.5 shrink-0" />
                                    <span class="truncate">{{ $calibracao->equipamento->vinculo_label }}</span>
                                </p>
                            </div>
                            <div class="mt-3 flex items-center justify-between gap-3 text-xs text-slate-400">
                                <span>{{ $calibracao->data_calibragem->format('d/m/Y') }}</span>
                                <span class="truncate">Registrado por {{ $calibracao->usuario?->name ?? '-' }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="app-alert-empty">Nenhuma calibração registrada ainda.</div>
                    @endforelse
                </div>
            </section>
            </div>
        </div>
    </div>
</x-app-layout>
