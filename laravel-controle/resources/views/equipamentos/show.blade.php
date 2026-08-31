<x-app-layout>
    <div class="pt-4 lg:pt-0">
        <div class="mx-auto w-full max-w-xl lg:max-w-7xl">
            @if (session('success'))
                <div class="mb-4 app-alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid gap-5 lg:grid-cols-[minmax(0,1.15fr)_minmax(360px,0.85fr)] lg:items-start">
                <div class="space-y-4">
                    <section class="app-card p-4 sm:p-5 lg:p-6">
                        @if ($equipamento->foto_path)
                            <button
                                type="button"
                                class="group relative mb-4 flex w-full items-center justify-center overflow-hidden rounded-[18px] border border-slate-700 bg-slate-950"
                                aria-label="Visualizar foto completa de {{ $equipamento->nome }}"
                                title="Visualizar foto completa"
                                x-data
                                x-on:click="$dispatch('open-modal', 'visualizar-foto-equipamento')"
                            >
                                <img
                                    src="{{ asset('storage/' . $equipamento->foto_path) }}"
                                    alt="{{ $equipamento->nome }}"
                                    class="block h-auto w-auto max-w-full object-contain transition duration-200 group-hover:scale-[1.01]"
                                    style="max-height: min(65dvh, 560px);"
                                >
                            </button>
                        @else
                            <div class="app-alert-empty mb-4">
                                <p class="text-sm font-semibold app-muted">
                                    Nenhuma foto registrada para esta ferramenta.
                                </p>

                                <a
                                    href="{{ route('equipamentos.edit', $equipamento) }}#foto"
                                    class="app-button app-button-secondary mt-3 w-full"
                                >
                                    Adicionar foto
                                </a>
                            </div>
                        @endif

                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">
                                    Detalhes
                                </p>

                                <h1 class="mt-1 text-2xl font-bold leading-tight text-white">
                                    {{ $equipamento->nome }}
                                </h1>
                            </div>

                            <span class="app-badge app-calibration-badge status-{{ $equipamento->ativo ? $equipamento->status_calibragem_key : 'inativa' }} max-w-[42%] justify-center truncate">
                                {{ $equipamento->ativo ? $equipamento->status_calibragem : 'Inativa' }}
                            </span>
                        </div>

                        <div class="mt-5 grid grid-cols-2 gap-3">
                            <div class="app-card-compact">
                                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-slate-500">
                                    Última
                                </p>
                                <p class="mt-2 text-sm font-semibold text-white">
                                    {{ $equipamento->ultima_calibragem?->format('d/m/Y') ?? 'Sem registro' }}
                                </p>
                            </div>

                            <div class="app-card-compact">
                                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-slate-500">
                                    Próxima
                                </p>
                                <p class="mt-2 text-sm font-semibold text-white">
                                    {{ $equipamento->proxima_calibragem?->format('d/m/Y') ?? '-' }}
                                </p>
                            </div>

                            <div class="app-card-compact">
                                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-slate-500">
                                    Período
                                </p>
                                <p class="mt-2 text-sm font-semibold text-white">
                                    {{ $equipamento->periodo_calibragem_dias }} dias
                                </p>
                            </div>

                            <div class="app-card-compact">
                                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-slate-500">
                                    {{ $equipamento->vinculo_tipo_label }}
                                </p>
                                <p class="mt-2 flex min-w-0 items-center gap-2 truncate text-sm font-semibold text-white">
                                    <x-vinculo-icon :type="$equipamento->tipo_vinculacao_efetivo" class="h-4 w-4 shrink-0" />
                                    <span class="truncate">{{ $equipamento->vinculo_label }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="mt-5 space-y-3 text-sm">
                            <div class="flex items-center justify-between gap-3 border-t border-slate-800 pt-3">
                                <span class="text-slate-500">Situação</span>
                                <span class="font-semibold {{ $equipamento->ativo ? 'text-emerald-400' : 'text-slate-400' }}">{{ $equipamento->status }}</span>
                            </div>

                            <div class="flex items-center justify-between gap-3 border-t border-slate-800 pt-3">
                                <span class="text-slate-500">Patrimônio</span>
                                <span class="truncate text-right text-white">
                                    {{ $equipamento->patrimonio ?? '-' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between gap-3 border-t border-slate-800 pt-3">
                                <span class="text-slate-500">Fabricante</span>
                                <span class="truncate text-right text-white">
                                    {{ $equipamento->fabricante ?? '-' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between gap-3 border-t border-slate-800 pt-3">
                                <span class="text-slate-500">Faixa de uso</span>
                                <span class="truncate text-right text-white">
                                    {{ $equipamento->faixa_uso ?? '-' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between gap-3 border-t border-slate-800 pt-3">
                                <span class="text-slate-500">Localização</span>
                                <span class="truncate text-right text-white">
                                    {{ $equipamento->localizacao ?? '-' }}
                                </span>
                            </div>
                        </div>
                    </section>

                    <section class="grid grid-cols-2 gap-3 lg:grid-cols-4">
                        @if ($equipamento->ativo)
                            <a href="{{ route('calibracoes.create', ['equipamento_id' => $equipamento->id]) }}" class="app-button app-button-primary">
                                Registrar
                            </a>
                        @else
                            <span class="app-button app-button-secondary cursor-not-allowed opacity-60" aria-disabled="true" title="Reative a ferramenta para registrar uma calibração">Inativa</span>
                        @endif

                        <a
                            href="{{ route('equipamentos.edit', $equipamento) }}"
                            class="app-button app-button-secondary"
                        >
                            Editar
                        </a>

                        <a
                            href="{{ route('equipamentos.index', $equipamento->ativo ? [] : ['status' => 'inativa']) }}"
                            class="app-button app-button-secondary"
                        >
                            Voltar
                        </a>

                        @if (auth()->user()->isAdmin())
                            <button
                                type="button"
                                class="app-button app-button-danger w-full"
                                x-data
                                x-on:click="$dispatch('open-modal', 'confirmar-exclusao-equipamento')"
                            >
                                Excluir
                            </button>
                        @endif
                    </section>
                </div>

                <section class="app-card p-4 sm:p-5 lg:p-6">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">
                                Histórico
                            </p>
                            <h2 class="mt-1 text-xl font-semibold text-white">
                                Calibrações
                            </h2>
                        </div>
                    </div>

                    <div class="mt-4 space-y-3">
                        @forelse ($equipamento->calibracoes->sortByDesc('data_calibragem') as $calibracao)
                            <div class="app-card-compact">
                                <div class="min-w-0">
                                    <p class="font-semibold text-white">
                                        {{ $calibracao->data_calibragem->format('d/m/Y') }}
                                    </p>
                                    <p class="mt-1 truncate text-sm text-slate-500">
                                        {{ $calibracao->usuario?->name ?? '-' }}
                                    </p>
                                </div>

                                @if ($calibracao->certificado)
                                    <p class="mt-3 text-xs font-semibold uppercase tracking-[0.12em] text-slate-400">
                                        Certificado: <span class="normal-case tracking-normal text-slate-200">{{ $calibracao->certificado }}</span>
                                    </p>
                                @endif

                                <p class="mt-2 text-sm leading-6 text-slate-300">
                                    {{ $calibracao->observacoes ?? 'Nenhuma observação registrada.' }}
                                </p>
                            </div>
                        @empty
                            <div class="app-alert-empty">
                                Nenhuma calibração registrada para este equipamento.
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>
    </div>

    @if ($equipamento->foto_path)
        <x-modal
            name="visualizar-foto-equipamento"
            focusable
            centered
            fit-content
        >
            <div class="p-2 sm:p-3">
                <div class="flex items-center justify-between gap-3 px-1 pb-2 sm:pb-3">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Foto da ferramenta</p>
                        <h2 class="mt-1 truncate text-lg font-bold text-white">{{ $equipamento->nome }}</h2>
                    </div>

                    <div class="flex shrink-0 items-center gap-2">
                        <a
                            href="{{ asset('storage/' . $equipamento->foto_path) }}"
                            download="{{ basename($equipamento->foto_path) }}"
                            class="app-icon-button app-button-secondary"
                            aria-label="Baixar foto"
                            title="Baixar foto"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M12 3v12"></path>
                                <path d="m7 10 5 5 5-5"></path>
                                <path d="M5 21h14"></path>
                            </svg>
                        </a>

                        <button
                            type="button"
                            class="app-icon-button app-button-secondary"
                            aria-label="Fechar visualização"
                            title="Fechar"
                            x-on:click="$dispatch('close')"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M18 6 6 18"></path>
                                <path d="m6 6 12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-center overflow-hidden rounded-[18px] border border-slate-700 bg-slate-950">
                    <img
                        src="{{ asset('storage/' . $equipamento->foto_path) }}"
                        alt="{{ $equipamento->nome }}"
                        class="block h-auto w-auto object-contain"
                        style="max-width: calc(100vw - 2rem); max-height: calc(100dvh - 7rem);"
                    >
                </div>
            </div>
        </x-modal>
    @endif

    @if (auth()->user()->isAdmin())
        <x-modal name="confirmar-exclusao-equipamento" focusable maxWidth="sm">
            <div class="p-5 sm:p-6">
                <div class="flex items-start gap-4">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-rose-500/15 text-xl font-black text-rose-400">
                        !
                    </div>

                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-rose-400">Excluir equipamento</p>
                        <h2 class="mt-2 text-xl font-bold text-white">Excluir {{ $equipamento->nome }}?</h2>
                        <p class="mt-2 text-sm leading-6 app-muted">
                            A ferramenta, sua foto e todo o histórico de calibrações serão removidos permanentemente.
                        </p>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3">
                    <button type="button" class="app-button app-button-secondary" x-on:click="$dispatch('close')">
                        Cancelar
                    </button>

                    <form method="POST" action="{{ route('equipamentos.destroy', $equipamento) }}">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="app-button app-button-danger w-full">Sim, excluir</button>
                    </form>
                </div>
            </div>
        </x-modal>
    @endif
</x-app-layout>
