<x-app-layout>
    <div class="pt-2 lg:pt-0">
        <div class="mx-auto w-full lg:max-w-7xl">
            @if (session('success'))
                <div class="mb-3 app-alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <section class="py-3 lg:app-card lg:p-6">
                <a href="{{ route('cadastros.index') }}" class="inline-flex items-center gap-2 text-[11px] font-black uppercase tracking-[0.16em] app-accent-text">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6" />
                    </svg>
                    Cadastros
                </a>
                <div class="mt-3 flex items-end justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-black leading-tight">Usuários</h1>
                        <p class="mt-1 text-sm app-muted">{{ $usuarios->count() }} usuário(s) cadastrado(s)</p>
                    </div>
                    <a href="{{ route('usuarios.create') }}" class="app-button app-button-primary shrink-0">Cadastrar usuário</a>
                </div>
            </section>

            <section class="mt-4 grid gap-3 lg:grid-cols-2 xl:grid-cols-3">
                @forelse ($usuarios as $usuario)
                    <div class="app-list-card app-card-hover">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0 border-l-4 border-cyan-400 pl-3">
                                <p class="truncate text-base font-black">{{ $usuario->name }}</p>
                                <p class="mt-1 truncate text-xs app-muted">Login: {{ $usuario->username }}</p>
                                <p class="mt-1 text-xs app-muted">Perfil: {{ ucfirst($usuario->perfil) }}</p>
                            </div>
                            <span class="app-badge">{{ ucfirst($usuario->perfil) }}</span>
                        </div>

                        <div class="mt-3 flex items-center gap-2">
                            <a href="{{ route('usuarios.edit', $usuario) }}" class="app-button app-button-secondary flex-1">Editar</a>
                            <button
                                type="button"
                                class="app-button app-button-danger flex-1"
                                x-data
                                x-on:click="$dispatch('open-modal', 'excluir-usuario-{{ $usuario->id }}')"
                            >
                                Excluir
                            </button>
                        </div>
                    </div>

                    <x-modal name="excluir-usuario-{{ $usuario->id }}" focusable maxWidth="sm">
                        <div class="p-5 sm:p-6">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-rose-400">Excluir usuário</p>
                            <h2 class="mt-2 text-xl font-bold">Excluir {{ $usuario->name }}?</h2>
                            <p class="mt-2 text-sm leading-6 app-muted">
                                O acesso será removido permanentemente. Ferramentas vinculadas a esta pessoa deverão receber um novo responsável.
                            </p>
                            <div class="mt-6 grid grid-cols-2 gap-3">
                                <button type="button" class="app-button app-button-secondary" x-on:click="$dispatch('close')">Cancelar</button>
                                <form method="POST" action="{{ route('usuarios.destroy', $usuario) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="app-button app-button-danger w-full">Sim, excluir</button>
                                </form>
                            </div>
                        </div>
                    </x-modal>
                @empty
                    <div class="app-alert-empty">Nenhum usuário cadastrado ainda.</div>
                @endforelse
            </section>
        </div>
    </div>
</x-app-layout>
