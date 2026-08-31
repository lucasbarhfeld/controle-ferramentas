<x-app-layout>
    <div class="pt-4 lg:pt-0">
        <div class="mx-auto w-full max-w-xl lg:max-w-5xl">
            <section class="app-card p-4 sm:p-5 lg:p-6">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Editar ferramenta</p>
                        <h1 class="mt-1 truncate text-2xl font-bold leading-tight text-white">{{ $equipamento->nome }}</h1>
                    </div>
                    <div class="grid h-12 w-12 shrink-0 place-items-center rounded-3xl app-button-primary text-xl font-bold">E</div>
                </div>

                <form method="POST" action="{{ route('equipamentos.update', $equipamento) }}" enctype="multipart/form-data" class="mt-6 space-y-5 lg:grid lg:grid-cols-2 lg:gap-5 lg:space-y-0">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-4 lg:col-span-2">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Nome</label>
                            <input type="text" name="nome" value="{{ old('nome', $equipamento->nome) }}" class="mt-2 app-input" />
                            @error('nome')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Patrimônio</label>
                            <input type="text" name="patrimonio" value="{{ old('patrimonio', $equipamento->patrimonio) }}" maxlength="100" class="mt-2 app-input" />
                            @error('patrimonio')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Fabricante</label>
                            <input type="text" name="fabricante" value="{{ old('fabricante', $equipamento->fabricante) }}" class="mt-2 app-input" />
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Localização</label>
                            <input type="text" name="localizacao" value="{{ old('localizacao', $equipamento->localizacao) }}" class="mt-2 app-input" />
                        </div>
                    </div>

                    <div class="lg:col-span-2">
                        <label class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Faixa de uso</label>
                        <input type="text" name="faixa_uso" value="{{ old('faixa_uso', $equipamento->faixa_uso) }}" class="mt-2 app-input" placeholder="Ex.: 0-150 mm, 0-10 bar, M6-M24" />
                        @error('faixa_uso')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <div
                        id="foto"
                        class="scroll-mt-24"
                        x-data="{
                            removerFoto: @js(old('remover_foto') === '1'),
                            temArquivo: false
                        }"
                    >
                        <label class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Foto da ferramenta</label>
                        <input type="hidden" name="remover_foto" :value="removerFoto ? '1' : '0'">
                        @if ($equipamento->foto_path)
                            <img
                                x-show="! removerFoto"
                                src="{{ asset('storage/' . $equipamento->foto_path) }}"
                                alt="{{ $equipamento->nome }}"
                                class="mt-2 aspect-video w-full rounded-[18px] border border-slate-700 object-cover"
                            >
                        @endif
                        <div class="mt-2 flex items-center gap-2">
                            <input
                                x-ref="foto"
                                type="file"
                                name="foto"
                                accept="image/*"
                                capture="environment"
                                class="app-input min-w-0 flex-1"
                                @change="temArquivo = $event.target.files.length > 0; if (temArquivo) removerFoto = false"
                            />
                            <button
                                x-show="temArquivo || @js((bool) $equipamento->foto_path)"
                                x-cloak
                                type="button"
                                class="app-icon-button app-button-secondary shrink-0"
                                @click="if (temArquivo) { $refs.foto.value = ''; temArquivo = false } else { removerFoto = ! removerFoto }"
                                :title="temArquivo ? 'Remover arquivo selecionado' : (removerFoto ? 'Manter foto atual' : 'Excluir foto atual')"
                                :aria-label="temArquivo ? 'Remover arquivo selecionado' : (removerFoto ? 'Manter foto atual' : 'Excluir foto atual')"
                            >
                                <svg x-show="! removerFoto || temArquivo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M18 6 6 18"></path>
                                    <path d="m6 6 12 12"></path>
                                </svg>
                                <svg x-show="removerFoto && ! temArquivo" x-cloak xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M9 14 4 9l5-5"></path>
                                    <path d="M4 9h10.5a5.5 5.5 0 0 1 0 11H11"></path>
                                </svg>
                            </button>
                        </div>
                        <p x-show="removerFoto" x-cloak class="mt-2 text-xs text-rose-400"></p>

                        @error('foto')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <x-vinculo-fields :usuarios="$usuarios" :centros-custo="$centrosCusto" :equipamento="$equipamento" />

                    <div class="grid gap-4 sm:grid-cols-2 lg:col-span-2">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Última calibração</label>
                            <input type="date" name="ultima_calibragem" value="{{ old('ultima_calibragem', $equipamento->ultima_calibragem ? $equipamento->ultima_calibragem->format('Y-m-d') : '') }}" class="mt-2 app-input" />
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Período</label>
                            <input type="number" name="periodo_calibragem_dias" value="{{ old('periodo_calibragem_dias', $equipamento->periodo_calibragem_dias) }}" min="1" class="mt-2 app-input" />
                            @error('periodo_calibragem_dias')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="lg:col-span-2">
                        <input type="hidden" name="ativo" value="0">
                        <label class="app-card-compact flex cursor-pointer items-center justify-between gap-4">
                            <span class="min-w-0">
                                <span class="block text-sm font-semibold text-white">Ferramenta ativa</span>
                                <span class="mt-1 block text-xs app-muted">Desmarque para inativar sem apagar os dados ou o histórico.</span>
                            </span>
                            <input type="checkbox" name="ativo" value="1" class="h-5 w-5 shrink-0 cursor-pointer accent-sky-500" @checked((bool) old('ativo', $equipamento->ativo))>
                        </label>
                        @error('ativo')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
                    </div>

                    <div class="app-form-actions lg:col-span-2">
                        <button type="submit" class="app-button app-button-primary">Salvar</button>
                        <a href="{{ route('equipamentos.show', $equipamento) }}" class="app-button app-button-secondary">Cancelar</a>
                    </div>
                </form>
            </section>
        </div>
    </div>
</x-app-layout>
