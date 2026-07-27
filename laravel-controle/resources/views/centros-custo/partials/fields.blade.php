@php
    $centro = $centroCusto ?? null;
@endphp

<div>
    <label for="codigo" class="app-label">Código</label>
    <input id="codigo" name="codigo" type="text" value="{{ old('codigo', $centro?->codigo) }}" class="mt-2 app-input" placeholder="Ex.: CC 16608" required>
    @error('codigo')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
</div>

<div>
    <label for="nome" class="app-label">Nome</label>
    <input id="nome" name="nome" type="text" value="{{ old('nome', $centro?->nome) }}" class="mt-2 app-input" placeholder="Ex.: Usinagem">
    @error('nome')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
</div>

<div class="lg:col-span-2">
    <label for="descricao" class="app-label">Descrição</label>
    <textarea id="descricao" name="descricao" rows="4" class="mt-2 app-textarea" placeholder="Informações adicionais sobre este centro de custo...">{{ old('descricao', $centro?->descricao) }}</textarea>
    @error('descricao')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
</div>

<label class="flex min-h-14 items-center justify-between gap-4 rounded-2xl border border-slate-700/60 bg-slate-950/25 px-4 py-3 lg:col-span-2">
    <span>
        <span class="block text-sm font-bold">Centro de custo ativo</span>
        <span class="mt-0.5 block text-xs app-muted">Centros inativos não aparecem em novos vínculos.</span>
    </span>
    <input type="hidden" name="ativo" value="0">
    <input
        type="checkbox"
        name="ativo"
        value="1"
        class="h-5 w-5 rounded border-slate-600 bg-slate-900 text-sky-500 focus:ring-sky-500"
        @checked((bool) old('ativo', $centro?->ativo ?? true))
    >
</label>
