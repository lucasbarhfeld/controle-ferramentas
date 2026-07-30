@php
    $equipamentosJson = $equipamentos->mapWithKeys(function ($equipamento) {
        return [
            $equipamento->id => [
                'id' => (string) $equipamento->id,
                'nome' => $equipamento->nome,
                'responsavel' => $equipamento->vinculo_label,
                'vinculoTipo' => $equipamento->vinculo_tipo_label,
                'localizacao' => $equipamento->localizacao ?? 'Localização não definida',
                'status' => $equipamento->status_calibragem,
                'statusKey' => $equipamento->status_calibragem_key,
                'diasRestantes' => $equipamento->dias_restantes,
                'diasLabel' => is_null($equipamento->dias_restantes)
                    ? '-'
                    : ($equipamento->dias_restantes < 0 ? $equipamento->dias_restantes . 'd' : $equipamento->dias_restantes . 'd'),
            ],
        ];
    });

    $selecionadosInicial = collect(old('equipamento_ids', $equipamentoSelecionadoId ? [$equipamentoSelecionadoId] : []))
        ->filter()
        ->map(fn ($id) => (string) $id)
        ->values();
@endphp

<x-app-layout>
    <div class="pt-4 lg:pt-0">
        <div class="mx-auto w-full max-w-xl lg:max-w-6xl">
            <section
                class="app-card p-4 sm:p-5 lg:p-6"
                x-data="{
                    selected: @js($selecionadosInicial),
                    equipamentoOpen: false,
                    equipamentos: @js($equipamentosJson),
                    equipamentoIds: @js($equipamentos->pluck('id')->map(fn ($id) => (string) $id)->values()),
                    get selecionados() {
                        return this.selected.map((id) => this.equipamentos[id]).filter(Boolean);
                    },
                    get selectedLabel() {
                        if (this.selected.length === 0) {
                            return 'Selecione uma ou mais ferramentas...';
                        }

                        if (this.selected.length === 1) {
                            const item = this.equipamentos[this.selected[0]];
                            return item ? `${item.nome} - ${item.responsavel}` : '1 ferramenta selecionada';
                        }

                        return `${this.selected.length} ferramentas selecionadas`;
                    },
                    toggleEquipamento(id) {
                        id = String(id);

                        if (this.selected.includes(id)) {
                            this.selected = this.selected.filter((selectedId) => selectedId !== id);
                            return;
                        }

                        this.selected = [...this.selected, id];
                    },
                    selecionarTodas() {
                        this.selected = [...this.equipamentoIds];
                    },
                    limparSelecao() {
                        this.selected = [];
                    }
                }"
            >
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] app-muted">Nova calibração</p>
                        <h1 class="mt-1 text-2xl font-bold leading-tight">Registrar auditoria</h1>
                    </div>
                    <div class="grid h-12 w-12 shrink-0 place-items-center rounded-3xl app-button-primary text-2xl font-black">+</div>
                </div>

                <form action="{{ route('calibracoes.store') }}" method="POST" class="mt-6 space-y-5 lg:grid lg:grid-cols-[minmax(0,1fr)_minmax(360px,0.75fr)] lg:gap-6 lg:space-y-0">
                    @csrf

                    <div class="space-y-2">
                        <div class="flex items-center justify-between gap-3">
                            <label class="text-xs font-semibold uppercase tracking-[0.24em] app-muted">Equipamentos</label>
                            <span class="text-[10px] font-black uppercase tracking-[0.12em] app-muted" x-text="`${selected.length} selecionada(s)`"></span>
                        </div>
                        <template x-for="id in selected" :key="id">
                            <input type="hidden" name="equipamento_ids[]" :value="id">
                        </template>
                        <div class="app-choice" @click.outside="equipamentoOpen = false">
                            <button type="button" class="app-choice-button" :class="{ 'is-open': equipamentoOpen }" @click="equipamentoOpen = !equipamentoOpen">
                                <span class="min-w-0 truncate" x-text="selectedLabel"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.2 7.2a1 1 0 0 1 1.4 0L10 10.6l3.4-3.4a1 1 0 1 1 1.4 1.4l-4.1 4.1a1 1 0 0 1-1.4 0L5.2 8.6a1 1 0 0 1 0-1.4z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="equipamentoOpen" x-cloak class="app-choice-menu">
                                <div class="mb-1 grid grid-cols-2 gap-1 border-b border-slate-700/60 pb-1">
                                    <button type="button" class="app-choice-option justify-center text-center" @click="selecionarTodas()">
                                        Marcar todas
                                    </button>
                                    <button type="button" class="app-choice-option justify-center text-center" @click="limparSelecao()">
                                        Limpar
                                    </button>
                                </div>
                                @foreach ($equipamentos as $equipamento)
                                    <button
                                        type="button"
                                        class="app-choice-option app-equipment-choice-option"
                                        :class="{ 'is-selected': selected.includes('{{ $equipamento->id }}') }"
                                        @click="toggleEquipamento('{{ $equipamento->id }}')"
                                    >
                                        <span class="app-checkbox" :class="{ 'is-checked': selected.includes('{{ $equipamento->id }}') }">
                                            <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.7 5.3a1 1 0 0 1 0 1.4l-7.1 7.1a1 1 0 0 1-1.4 0L3.3 9a1 1 0 0 1 1.4-1.4l4.2 4.2 6.4-6.4a1 1 0 0 1 1.4-.1z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                        <x-vinculo-icon :type="$equipamento->tipo_vinculacao_efetivo" class="h-4 w-4 shrink-0" />
                                        <span class="app-equipment-choice-label min-w-0 truncate">{{ $equipamento->nome }} - {{ $equipamento->vinculo_label }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        @error('equipamento_ids')<p class="text-sm text-rose-400">{{ $message }}</p>@enderror
                        @error('equipamento_ids.*')<p class="text-sm text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <div x-show="selecionados.length > 0" x-cloak class="space-y-2">
                        <template x-for="item in selecionados" :key="item.id">
                            <div class="app-list-card app-tool-preview">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="min-w-0 app-calibration-marker" :class="`status-${item.statusKey || 'sem-calibracao'}`">
                                        <p class="truncate text-base font-black" x-text="item.nome"></p>
                                    </div>
                                    <span class="app-badge app-calibration-badge max-w-[42%] justify-center truncate" :class="`status-${item.statusKey || 'sem-calibracao'}`" x-text="item.status"></span>
                                </div>

                                <div class="mt-3 flex items-center justify-between gap-3 text-xs text-slate-400">
                                    <div class="min-w-0">
                                        <p class="truncate" x-text="item.responsavel"></p>
                                        <p class="mt-0.5 truncate text-[10px] uppercase tracking-[0.08em] opacity-70" x-text="item.vinculoTipo"></p>
                                        <p class="mt-0.5 truncate" x-text="item.localizacao"></p>
                                    </div>
                                    <p class="app-calibration-days shrink-0 font-black" :class="`status-${item.statusKey || 'sem-calibracao'}`" x-text="item.diasLabel"></p>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="space-y-2 lg:col-start-2 lg:row-start-1">
                        <label class="text-xs font-semibold uppercase tracking-[0.24em] app-muted">Data</label>
                        <input type="date" name="data_calibragem" value="{{ old('data_calibragem', now()->format('Y-m-d')) }}" class="app-input" required>
                        @error('data_calibragem')<p class="text-sm text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <div class="space-y-2 lg:col-start-2">
                        <label class="text-xs font-semibold uppercase tracking-[0.24em] app-muted">Observações</label>
                        <textarea name="observacoes" rows="4" class="app-textarea" placeholder="Condições, desvios, ações corretivas...">{{ old('observacoes') }}</textarea>
                        @error('observacoes')<p class="text-sm text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2 lg:col-start-2">
                        <button type="submit" class="app-button app-button-primary">Registrar</button>
                        <a href="{{ route('equipamentos.index') }}" class="app-button app-button-secondary">Cancelar</a>
                    </div>
                </form>
            </section>
        </div>
    </div>
</x-app-layout>
