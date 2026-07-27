@props([
    'usuarios',
    'centrosCusto',
    'equipamento' => null,
])

@php
    $tipoInicial = old(
        'tipo_vinculacao',
        $equipamento?->tipo_vinculacao_efetivo ?? \App\Models\Equipamento::VINCULO_SEM_RESPONSAVEL,
    );
    $usuarioInicial = (string) old('usuario_responsavel_id', $equipamento?->usuario_responsavel_id ?? '');
    $centroInicial = (string) old('centro_custo_id', $equipamento?->centro_custo_id ?? '');
    $usuarioSelecionado = $usuarioInicial !== '' ? $usuarios->firstWhere('id', (int) $usuarioInicial) : null;
    $centroSelecionado = $centroInicial !== '' ? $centrosCusto->firstWhere('id', (int) $centroInicial) : null;
@endphp

<div
    class="lg:col-span-2"
    x-data="{
        tipo: @js($tipoInicial),
        usuario: @js($usuarioInicial),
        usuarioLabel: @js($usuarioSelecionado?->name ?? 'Selecione uma pessoa...'),
        centro: @js($centroInicial),
        centroLabel: @js($centroSelecionado?->label ?? 'Selecione um centro de custo...'),
        usuarioOpen: false,
        centroOpen: false,
    }"
>
    <input type="hidden" name="tipo_vinculacao" :value="tipo">
    <input type="hidden" name="usuario_responsavel_id" :value="usuario">
    <input type="hidden" name="centro_custo_id" :value="centro">

    <label class="text-xs font-semibold uppercase tracking-[0.24em] app-muted">Tipo de vinculação</label>

    <div class="mt-2 grid grid-cols-2 gap-2 lg:grid-cols-4">
        <button
            type="button"
            class="app-vinculo-type app-vinculo-usuario"
            :class="{ 'is-active': tipo === 'usuario' }"
            @click="tipo = 'usuario'"
        >
            <span class="app-vinculo-type-icon">
                <x-vinculo-icon type="usuario" class="h-5 w-5" />
            </span>
            <span class="min-w-0">
                <span class="block text-sm font-bold">Pessoa</span>
                <span class="mt-0.5 block text-[10px] leading-4 opacity-80">Usuário responsável</span>
            </span>
        </button>

        <button
            type="button"
            class="app-vinculo-type app-vinculo-armario"
            :class="{ 'is-active': tipo === 'armario_coletivo' }"
            @click="tipo = 'armario_coletivo'"
        >
            <span class="app-vinculo-type-icon">
                <x-vinculo-icon type="armario_coletivo" class="h-5 w-5" />
            </span>
            <span class="min-w-0">
                <span class="block text-sm font-bold">Armário</span>
                <span class="mt-0.5 block text-[10px] leading-4 opacity-80">Uso coletivo</span>
            </span>
        </button>

        <button
            type="button"
            class="app-vinculo-type app-vinculo-centro"
            :class="{ 'is-active': tipo === 'centro_custo' }"
            @click="tipo = 'centro_custo'"
        >
            <span class="app-vinculo-type-icon">
                <x-vinculo-icon type="centro_custo" class="h-5 w-5" />
            </span>
            <span class="min-w-0">
                <span class="block text-sm font-bold">Centro de custo</span>
                <span class="mt-0.5 block text-[10px] leading-4 opacity-80">Setor responsável</span>
            </span>
        </button>

        <button
            type="button"
            class="app-vinculo-type app-vinculo-sem"
            :class="{ 'is-active': tipo === 'sem_responsavel' }"
            @click="tipo = 'sem_responsavel'"
        >
            <span class="app-vinculo-type-icon">
                <x-vinculo-icon type="sem_responsavel" class="h-5 w-5" />
            </span>
            <span class="min-w-0">
                <span class="block text-sm font-bold">Sem vínculo</span>
                <span class="mt-0.5 block text-[10px] leading-4 opacity-80">Não atribuído</span>
            </span>
        </button>
    </div>

    <div x-show="tipo === 'usuario'" x-cloak class="mt-3">
        <label class="text-xs font-semibold uppercase tracking-[0.2em] app-muted">Pessoa responsável</label>
        <div class="app-choice mt-2" @click.outside="usuarioOpen = false">
            <button type="button" class="app-choice-button" :class="{ 'is-open': usuarioOpen }" @click="usuarioOpen = !usuarioOpen">
                <span class="flex min-w-0 items-center gap-2">
                    <x-vinculo-icon type="usuario" class="h-4 w-4 shrink-0" />
                    <span class="truncate" x-text="usuarioLabel"></span>
                </span>
                <svg class="app-choice-chevron" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.2 7.2a1 1 0 0 1 1.4 0L10 10.6l3.4-3.4a1 1 0 1 1 1.4 1.4l-4.1 4.1a1 1 0 0 1-1.4 0L5.2 8.6a1 1 0 0 1 0-1.4z" clip-rule="evenodd" />
                </svg>
            </button>
            <div x-show="usuarioOpen" x-cloak class="app-choice-menu">
                @foreach ($usuarios as $usuario)
                    <button
                        type="button"
                        class="app-choice-option"
                        :class="{ 'is-selected': usuario === '{{ $usuario->id }}' }"
                        @click="usuario = '{{ $usuario->id }}'; usuarioLabel = @js($usuario->name); usuarioOpen = false"
                    >
                        <x-vinculo-icon type="usuario" class="h-4 w-4 shrink-0" />
                        <span class="min-w-0 truncate">{{ $usuario->name }}</span>
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <div x-show="tipo === 'centro_custo'" x-cloak class="mt-3">
        <div class="flex items-center justify-between gap-3">
            <label class="text-xs font-semibold uppercase tracking-[0.2em] app-muted">Centro de custo</label>
            @if (auth()->user()?->isAdmin())
                <a href="{{ route('centros-custo.create') }}" class="text-[10px] font-black uppercase tracking-[0.12em] app-accent-text">
                    Cadastrar novo
                </a>
            @endif
        </div>
        <div class="app-choice mt-2" @click.outside="centroOpen = false">
            <button type="button" class="app-choice-button" :class="{ 'is-open': centroOpen }" @click="centroOpen = !centroOpen">
                <span class="flex min-w-0 items-center gap-2">
                    <x-vinculo-icon type="centro_custo" class="h-4 w-4 shrink-0" />
                    <span class="truncate" x-text="centroLabel"></span>
                </span>
                <svg class="app-choice-chevron" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.2 7.2a1 1 0 0 1 1.4 0L10 10.6l3.4-3.4a1 1 0 1 1 1.4 1.4l-4.1 4.1a1 1 0 0 1-1.4 0L5.2 8.6a1 1 0 0 1 0-1.4z" clip-rule="evenodd" />
                </svg>
            </button>
            <div x-show="centroOpen" x-cloak class="app-choice-menu">
                @forelse ($centrosCusto as $centroCusto)
                    <button
                        type="button"
                        class="app-choice-option"
                        :class="{ 'is-selected': centro === '{{ $centroCusto->id }}' }"
                        @click="centro = '{{ $centroCusto->id }}'; centroLabel = @js($centroCusto->label); centroOpen = false"
                    >
                        <x-vinculo-icon type="centro_custo" class="h-4 w-4 shrink-0" />
                        <span class="min-w-0 truncate">{{ $centroCusto->label }}</span>
                    </button>
                @empty
                    <div class="px-3 py-4 text-center text-sm app-muted">Nenhum centro de custo ativo.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div x-show="tipo === 'armario_coletivo'" x-cloak class="app-vinculo-notice">
        A ferramenta ficará disponível no armário coletivo, sem ser associada a um usuário.
    </div>

    @error('tipo_vinculacao')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
    @error('usuario_responsavel_id')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
    @error('centro_custo_id')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
</div>
