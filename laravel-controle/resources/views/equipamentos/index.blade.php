@php
    $statusAtual = request('status');
    $filtrosAtivos = $busca !== '' || $responsavelId;
    $parametrosBase = array_filter([
        'busca' => $busca,
        'responsavel' => $responsavelId,
    ], fn ($valor) => $valor !== null && $valor !== '');
    $filtros = [
        ['label' => 'Todas', 'status' => null, 'count' => $statusCounts['todas'] ?? 0],
        ['label' => 'Vencidas', 'status' => 'vencida', 'count' => $statusCounts['vencida'] ?? 0],
        ['label' => 'Críticas', 'status' => 'critica', 'count' => $statusCounts['critica'] ?? 0],
        ['label' => 'Atenção', 'status' => 'proxima', 'count' => $statusCounts['proxima'] ?? 0],
        ['label' => 'Em dia', 'status' => 'em_dia', 'count' => $statusCounts['em_dia'] ?? 0],
    ];
@endphp

<x-app-layout>
    <div class="pt-2 lg:pt-0">
        <div class="mx-auto w-full lg:max-w-7xl">
            @if (session('success'))
                <div class="mb-3 app-alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('import_warnings') && count(session('import_warnings')))
                <div class="app-alert-empty mb-3 text-left">
                    <p class="font-semibold">A importação foi concluída com observações:</p>
                    <ul class="mt-2 space-y-1 text-sm app-muted">
                        @foreach (session('import_warnings') as $warning)
                            <li>• {{ $warning }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="py-3 lg:app-card lg:p-6">
                <div class="flex items-end justify-between gap-4">
                    <div class="min-w-0">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.3em] app-accent-text">Ferramentas</p>
                        <h1 class="mt-2 text-2xl font-black leading-tight lg:text-4xl">Total: {{ $equipamentos->count() }}</h1>
                    </div>
                    <div class="flex shrink-0 items-center gap-2">
                        @if (auth()->user()->isAdmin())
                            <button
                                type="button"
                                class="app-icon-button app-button-secondary"
                                aria-label="Importar ferramentas"
                                title="Importar ferramentas"
                                x-data
                                x-on:click="$dispatch('open-modal', 'importar-equipamentos')"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16V6m0 0-4 4m4-4 4 4M5 14v4a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-4" />
                                </svg>
                            </button>
                        @endif

                        <a href="{{ route('equipamentos.export') }}" class="app-icon-button app-button-secondary" aria-label="Exportar ferramentas">
                            <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v10m0 0 4-4m-4 4-4-4M5 14v4a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-4" />
                            </svg>
                        </a>
                        <a href="{{ route('equipamentos.create') }}" class="app-button app-button-primary">Cadastrar</a>
                    </div>
                </div>

                <form method="GET" action="{{ route('equipamentos.index') }}" class="app-tool-filters">
                    @if ($statusAtual)
                        <input type="hidden" name="status" value="{{ $statusAtual }}">
                    @endif

                    <div class="app-tool-search">
                        <label for="busca-equipamento" class="sr-only">Buscar pelo nome do equipamento</label>
                        <svg xmlns="http://www.w3.org/2000/svg" class="app-tool-search-icon app-muted" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7" />
                            <path stroke-linecap="round" d="m20 20-4-4" />
                        </svg>
                        <input
                            id="busca-equipamento"
                            type="search"
                            name="busca"
                            value="{{ $busca }}"
                            maxlength="100"
                            placeholder="Buscar equipamento pelo nome"
                            class="app-input"
                        >
                    </div>

                    <div class="app-tool-responsavel">
                        <label for="filtro-responsavel" class="sr-only">Filtrar por responsável</label>
                        <select id="filtro-responsavel" name="responsavel" class="app-select">
                            <option value="">Todos os responsáveis</option>
                            @foreach ($usuariosFiltro as $usuario)
                                <option value="{{ $usuario->id }}" @selected((string) $responsavelId === (string) $usuario->id)>
                                    {{ $usuario->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="app-tool-filter-actions">
                        <button type="submit" class="app-button app-button-primary">
                            Filtrar
                        </button>

                        @if ($filtrosAtivos)
                            <a
                                href="{{ route('equipamentos.index', array_filter(['status' => $statusAtual])) }}"
                                class="app-icon-button app-button-secondary"
                                aria-label="Limpar busca e responsável"
                                title="Limpar filtros"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                    <path stroke-linecap="round" d="m6 6 12 12M18 6 6 18" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </form>

                <div class="app-filter-strip mt-3">
                    @foreach ($filtros as $filtro)
                        @php
                            $parametros = $parametrosBase;

                            if ($filtro['status']) {
                                $parametros['status'] = $filtro['status'];
                            }
                        @endphp
                        <a
                            href="{{ route('equipamentos.index', $parametros) }}"
                            class="app-filter-chip {{ $loop->first ? 'is-wide' : '' }} {{ $statusAtual === $filtro['status'] || (!$statusAtual && !$filtro['status']) ? 'is-active' : '' }}"
                        >
                            <span>{{ $filtro['label'] }}</span>
                            <span class="app-filter-count">{{ $filtro['count'] }}</span>
                        </a>
                    @endforeach
                </div>
            </section>

            <section class="mt-4 grid gap-3 lg:grid-cols-2 xl:grid-cols-3">
                @forelse ($equipamentos as $equipamento)
                    <a href="{{ route('equipamentos.show', $equipamento) }}" class="app-list-card app-card-hover block text-left">
                        <div class="flex items-center justify-between gap-3">
                            <div class="min-w-0 app-calibration-marker status-{{ $equipamento->status_calibragem_key }}">
                                <p class="truncate text-base font-black">{{ $equipamento->nome }}</p>
                            </div>
                            <span class="app-badge app-calibration-badge status-{{ $equipamento->status_calibragem_key }} max-w-[42%] justify-center truncate">{{ $equipamento->status_calibragem }}</span>
                        </div>

                        <div class="mt-3 flex items-center justify-between gap-3 text-xs text-slate-400">
                            <div class="min-w-0">
                                <p class="flex min-w-0 items-center gap-1.5 truncate">
                                    <x-vinculo-icon :type="$equipamento->tipo_vinculacao_efetivo" class="h-3.5 w-3.5 shrink-0" />
                                    <span class="truncate">{{ $equipamento->vinculo_label }}</span>
                                </p>
                                <p class="mt-0.5 truncate">{{ $equipamento->faixa_uso ?? $equipamento->localizacao ?? 'Localização não definida' }}</p>
                            </div>
                            <p class="app-calibration-days status-{{ $equipamento->status_calibragem_key }} shrink-0 font-black">
                                {{ is_null($equipamento->dias_restantes) ? '-' : $equipamento->dias_restantes . 'd' }}
                            </p>
                        </div>
                    </a>
                @empty
                    <div class="app-alert-empty">
                        Nenhuma ferramenta encontrada.
                    </div>
                @endforelse
            </section>
        </div>
    </div>

    @if (auth()->user()->isAdmin())
        <x-modal name="importar-equipamentos" :show="$errors->has('arquivo')" focusable maxWidth="md">
            <form method="POST" action="{{ route('equipamentos.import') }}" enctype="multipart/form-data" class="p-5 sm:p-6">
                @csrf

                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] app-accent-text">Importação</p>
                        <h2 class="mt-2 text-xl font-bold text-white">Importar ferramentas</h2>
                        <p class="mt-2 text-sm leading-6 app-muted">
                            Selecione uma planilha XLSX, XLS ou CSV. O cabeçalho pode estar nas primeiras 30 linhas.
                        </p>
                    </div>

                    <button
                        type="button"
                        class="app-icon-button app-button-secondary shrink-0"
                        aria-label="Fechar"
                        x-on:click="$dispatch('close')"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" d="m6 6 12 12M18 6 6 18" />
                        </svg>
                    </button>
                </div>

                <div class="mt-5">
                    <label for="arquivo-importacao" class="app-label">Planilha</label>
                    <input
                        id="arquivo-importacao"
                        type="file"
                        name="arquivo"
                        accept=".xlsx,.xls,.csv"
                        required
                        class="mt-2 app-input"
                    >
                    @error('arquivo')
                        <p class="mt-2 text-sm text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="app-card-compact mt-4 text-sm app-muted">
                    <p class="font-semibold text-white">Colunas reconhecidas</p>
                    <p class="mt-1 leading-6">
                        Descrição, Fabricante, Faixa de uso, Responsável, Setor, Data calibração e Período (anos ou dias).
                    </p>
                    <p class="mt-2 leading-6">
                        O responsável pode ser um usuário, Armário coletivo ou um centro de custo como CC 16608. Quando o período estiver vazio, será usado o padrão de 360 dias.
                    </p>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3">
                    <button type="button" class="app-button app-button-secondary" x-on:click="$dispatch('close')">
                        Cancelar
                    </button>
                    <button type="submit" class="app-button app-button-primary">
                        Importar
                    </button>
                </div>
            </form>
        </x-modal>
    @endif
</x-app-layout>
