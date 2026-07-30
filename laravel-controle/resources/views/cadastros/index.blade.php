<x-app-layout>
    <div class="pt-2 lg:pt-0">
        <div class="mx-auto w-full lg:max-w-6xl">
            <section class="py-3 lg:app-card lg:p-6">
                <p class="text-[11px] font-semibold uppercase tracking-[0.3em] app-accent-text">Administração</p>
                <h1 class="mt-2 text-2xl font-black leading-tight lg:text-4xl">Cadastros</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 app-muted">
                </p>
            </section>

            <section class="mt-4 grid gap-4 lg:grid-cols-2">
                <article class="app-list-card app-card-hover flex min-h-56 flex-col">
                    <div class="flex items-start gap-4">
                        <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-sky-500/15 text-sky-300">
                            <x-vinculo-icon type="usuario" />
                        </span>
                        <div class="min-w-0">
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] app-muted">Acessos</p>
                            <h2 class="mt-1 text-xl font-black">Usuários</h2>
                            <p class="mt-2 text-sm leading-6 app-muted">
                            </p>
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-2">
                        <div class="rounded-xl border border-slate-700/60 px-3 py-3">
                            <span class="block text-2xl font-black">{{ $resumo['usuarios'] }}</span>
                            <span class="text-[10px] font-bold uppercase tracking-[0.12em] app-muted">Cadastrados</span>
                        </div>
                        <div class="rounded-xl border border-slate-700/60 px-3 py-3">
                            <span class="block text-2xl font-black">{{ $resumo['administradores'] }}</span>
                            <span class="text-[10px] font-bold uppercase tracking-[0.12em] app-muted">Administradores</span>
                        </div>
                    </div>

                    <div class="mt-auto flex gap-2 pt-5">
                        <a href="{{ route('usuarios.index') }}" class="app-button app-button-primary flex-1">Gerenciar usuários</a>
                        <a href="{{ route('usuarios.create') }}" class="app-button app-button-secondary px-4" aria-label="Cadastrar usuário" title="Cadastrar usuário">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" d="M12 5v14M5 12h14" />
                            </svg>
                        </a>
                    </div>
                </article>

                <article class="app-list-card app-card-hover flex min-h-56 flex-col">
                    <div class="flex items-start gap-4">
                        <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-emerald-500/15 text-emerald-300">
                            <x-vinculo-icon type="centro_custo" />
                        </span>
                        <div class="min-w-0">
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] app-muted">Organização</p>
                            <h2 class="mt-1 text-xl font-black">Centros de custo</h2>
                            <p class="mt-2 text-sm leading-6 app-muted">
                            </p>
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-2">
                        <div class="rounded-xl border border-slate-700/60 px-3 py-3">
                            <span class="block text-2xl font-black">{{ $resumo['centros_custo'] }}</span>
                            <span class="text-[10px] font-bold uppercase tracking-[0.12em] app-muted">Cadastrados</span>
                        </div>
                        <div class="rounded-xl border border-slate-700/60 px-3 py-3">
                            <span class="block text-2xl font-black">{{ $resumo['centros_ativos'] }}</span>
                            <span class="text-[10px] font-bold uppercase tracking-[0.12em] app-muted">Ativos</span>
                        </div>
                    </div>

                    <div class="mt-auto flex gap-2 pt-5">
                        <a href="{{ route('centros-custo.index') }}" class="app-button app-button-primary flex-1">Gerenciar centros</a>
                        <a href="{{ route('centros-custo.create') }}" class="app-button app-button-secondary px-4" aria-label="Cadastrar centro de custo" title="Cadastrar centro de custo">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" d="M12 5v14M5 12h14" />
                            </svg>
                        </a>
                    </div>
                </article>
            </section>
        </div>
    </div>
</x-app-layout>
