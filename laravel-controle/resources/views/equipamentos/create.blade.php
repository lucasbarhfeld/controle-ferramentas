<x-app-layout>
    <div class="pt-4 lg:pt-0">
        <div class="mx-auto w-full max-w-xl lg:max-w-5xl">
            <section class="app-card p-4 sm:p-5 lg:p-6">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Nova ferramenta</p>
                        <h1 class="mt-1 text-2xl font-bold leading-tight text-white">Cadastrar equipamento</h1>
                    </div>
                    <div class="grid h-12 w-12 shrink-0 place-items-center rounded-3xl app-button-primary text-2xl font-black">+</div>
                </div>

                <form method="POST" action="{{ route('equipamentos.store') }}" enctype="multipart/form-data" class="mt-6 space-y-5 lg:grid lg:grid-cols-2 lg:gap-5 lg:space-y-0">
                    @csrf

                    <div class="grid gap-4 lg:col-span-2">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Nome</label>
                            <input type="text" name="nome" value="{{ old('nome') }}" class="mt-2 app-input" />
                            @error('nome')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Patrimônio</label>
                            <input type="text" name="patrimonio" value="{{ old('patrimonio') }}" maxlength="100" class="mt-2 app-input" />
                            @error('patrimonio')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Fabricante</label>
                            <input type="text" name="fabricante" value="{{ old('fabricante') }}" class="mt-2 app-input" />
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Localização</label>
                            <input type="text" name="localizacao" value="{{ old('localizacao') }}" class="mt-2 app-input" />
                        </div>
                    </div>

                    <div class="lg:col-span-2">
                        <label class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Faixa de uso</label>
                        <input type="text" name="faixa_uso" value="{{ old('faixa_uso') }}" class="mt-2 app-input" placeholder="Ex.: 0-150 mm, 0-10 bar, M6-M24" />
                        @error('faixa_uso')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <div x-data="{ temArquivo: false }">
                        <label class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Foto da ferramenta</label>
                        <div class="mt-2 flex items-center gap-2">
                            <input
                                x-ref="foto"
                                type="file"
                                name="foto"
                                accept="image/*"
                                capture="environment"
                                class="app-input min-w-0 flex-1"
                                @change="temArquivo = $event.target.files.length > 0"
                            />
                            <button
                                x-show="temArquivo"
                                x-cloak
                                type="button"
                                class="app-icon-button app-button-secondary shrink-0"
                                title="Remover arquivo selecionado"
                                aria-label="Remover arquivo selecionado"
                                @click="$refs.foto.value = ''; temArquivo = false"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M18 6 6 18"></path>
                                    <path d="m6 6 12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <p class="mt-2 text-xs app-muted">No celular, este campo abre a câmera para registrar o equipamento.</p>
                        @error('foto')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <x-vinculo-fields :usuarios="$usuarios" :centros-custo="$centrosCusto" />

                    <div class="grid gap-4 sm:grid-cols-2 lg:col-span-2">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Última calibração</label>
                            <input type="date" name="ultima_calibragem" value="{{ old('ultima_calibragem') }}" class="mt-2 app-input" />
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Período</label>
                            <input type="number" name="periodo_calibragem_dias" value="{{ old('periodo_calibragem_dias', 360) }}" min="1" class="mt-2 app-input" />
                            @error('periodo_calibragem_dias')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="app-form-actions lg:col-span-2">
                        <button type="submit" class="app-button app-button-primary">Salvar</button>
                        <a href="{{ route('equipamentos.index') }}" class="app-button app-button-secondary">Cancelar</a>
                    </div>
                </form>
            </section>
        </div>
    </div>
</x-app-layout>
