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

                    <div class="lg:col-span-2">
                        <label class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Nome</label>
                        <input type="text" name="nome" value="{{ old('nome', $equipamento->nome) }}" class="mt-2 app-input" />
                        @error('nome')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
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

                    <div id="foto" class="scroll-mt-24">
                        <label class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Foto da ferramenta</label>
                        @if ($equipamento->foto_path)
                            <img src="{{ asset('storage/' . $equipamento->foto_path) }}" alt="{{ $equipamento->nome }}" class="mt-2 aspect-video w-full rounded-[18px] border border-slate-700 object-cover">
                        @endif
                        <input type="file" name="foto" accept="image/*" capture="environment" class="mt-2 app-input" />
                        <p class="mt-2 text-xs app-muted">No celular, este campo abre a câmera para registrar o equipamento. Enviar uma nova foto substitui a foto atual.</p>
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

                    <div class="app-form-actions lg:col-span-2">
                        <button type="submit" class="app-button app-button-primary">Salvar</button>
                        <a href="{{ route('equipamentos.show', $equipamento) }}" class="app-button app-button-secondary">Cancelar</a>
                    </div>
                </form>
            </section>
        </div>
    </div>
</x-app-layout>
