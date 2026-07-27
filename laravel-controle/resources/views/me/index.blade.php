<x-app-layout>
    <div class="pt-2 lg:pt-0">
        <div class="mx-auto w-full lg:grid lg:max-w-7xl lg:grid-cols-[360px_minmax(0,1fr)] lg:gap-6 lg:items-start">
            <section class="py-3 lg:app-card lg:p-6">
                <div class="flex items-center gap-3">
                    <div class="grid h-12 w-12 shrink-0 place-items-center rounded-[18px] border border-amber-500/40 bg-amber-500 text-xl font-black text-slate-950">
                        {{ strtoupper(substr($usuario->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.3em] text-sky-300/80">Minha Área</p>
                        <h1 class="mt-1 truncate text-2xl font-black leading-tight text-white">{{ $usuario->name }}</h1>
                        <p class="mt-0.5 text-xs text-slate-400">{{ ucfirst($usuario->perfil) }}</p>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-3">
                    <div class="app-status-card">
                        <p class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-500">Ferramentas</p>
                        <p class="mt-1 text-2xl font-black text-white">{{ $equipamentos->count() }}</p>
                    </div>
                    <div class="app-status-card">
                        <p class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-500">Calibrações</p>
                        <p class="mt-1 text-2xl font-black text-white">{{ $calibracoes->count() }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="app-button app-button-danger w-full">Sair da conta</button>
                </form>
            </section>

            <div class="lg:space-y-6">
            <section class="mt-3 lg:mt-0 lg:app-card lg:p-5">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.3em] text-slate-400">Responsável</p>
                        <h2 class="mt-1 text-lg font-black text-white">Suas ferramentas</h2>
                    </div>
                    <a href="{{ route('equipamentos.index') }}" class="text-[11px] font-black uppercase tracking-[0.12em] text-amber-400">Ver todas</a>
                </div>

                <div class="mt-3 space-y-3">
                    @forelse ($equipamentos as $equipamento)
                        <a href="{{ route('equipamentos.show', $equipamento) }}" class="app-list-card app-card-hover block text-left">
                            <div class="flex items-center justify-between gap-3">
                                <div class="min-w-0 app-calibration-marker status-{{ $equipamento->status_calibragem_key }}">
                                    <p class="truncate font-black text-white">{{ $equipamento->nome }}</p>
                                </div>
                                <span class="app-badge app-calibration-badge status-{{ $equipamento->status_calibragem_key }} max-w-[42%] justify-center truncate">{{ $equipamento->status_calibragem }}</span>
                            </div>
                            <div class="mt-3 flex items-center justify-between gap-3 text-xs text-slate-400">
                                <span>{{ $equipamento->ultima_calibragem?->format('d/m/Y') ?? 'Sem registro' }}</span>
                                <span>{{ $equipamento->proxima_calibragem?->format('d/m/Y') ?? '-' }}</span>
                            </div>
                        </a>
                    @empty
                        <div class="app-alert-empty">Nenhuma ferramenta vinculada a você.</div>
                    @endforelse
                </div>
            </section>

            <section class="mt-5 lg:mt-0 lg:app-card lg:p-5">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.3em] text-slate-400">Últimas calibrações</p>
                        <h2 class="mt-1 text-lg font-black text-white">Histórico recente</h2>
                    </div>
                    <a href="{{ route('calibracoes.index') }}" class="text-[11px] font-black uppercase tracking-[0.12em] text-amber-400">Ver todas</a>
                </div>

                <div class="mt-3 space-y-3">
                    @forelse ($calibracoes->take(5) as $calibracao)
                        <div class="app-list-card">
                            <div class="min-w-0">
                                <p class="truncate font-black text-white">{{ $calibracao->equipamento->nome }}</p>
                            </div>
                            <div class="mt-3 flex items-center justify-between gap-3 text-xs text-slate-400">
                                <span>{{ $calibracao->data_calibragem->format('d/m/Y') }}</span>
                                <span class="truncate">{{ $calibracao->usuario?->name ?? '-' }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="app-alert-empty">Nenhuma calibração realizada por você ainda.</div>
                    @endforelse
                </div>
            </section>
            </div>
        </div>
    </div>
</x-app-layout>
