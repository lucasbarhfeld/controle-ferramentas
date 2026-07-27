@props(['active'])

<div class="mt-4 grid grid-cols-2 gap-2 rounded-2xl border border-slate-700/60 bg-slate-950/20 p-1.5 lg:max-w-xl">
    <a
        href="{{ route('usuarios.index') }}"
        class="flex min-h-11 items-center justify-center gap-2 rounded-xl px-3 text-xs font-black uppercase tracking-[0.1em] transition {{ $active === 'usuarios' ? 'app-button-primary' : 'app-muted hover:bg-slate-700/30' }}"
    >
        <x-vinculo-icon type="usuario" class="h-4 w-4" />
        Usuários
    </a>
    <a
        href="{{ route('centros-custo.index') }}"
        class="flex min-h-11 items-center justify-center gap-2 rounded-xl px-3 text-xs font-black uppercase tracking-[0.1em] transition {{ $active === 'centros-custo' ? 'app-button-primary' : 'app-muted hover:bg-slate-700/30' }}"
    >
        <x-vinculo-icon type="centro_custo" class="h-4 w-4" />
        Centros de custo
    </a>
</div>
