<x-app-layout>
    <div class="pt-2 lg:pt-0">
        <div class="mx-auto w-full lg:max-w-7xl">
            @if (session('success'))
                <div class="mb-3 app-alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <section class="py-3 lg:app-card lg:p-6">
                <p class="text-[11px] font-semibold uppercase tracking-[0.3em] text-sky-300/80">Administração</p>
                <h1 class="mt-2 text-2xl font-black leading-tight text-white">Usuários</h1>
                <p class="mt-1 text-sm text-slate-400">{{ $usuarios->count() }} usuário(s) cadastrado(s)</p>

                <x-admin-tabs active="usuarios" />

                <div class="mt-4 grid gap-3 lg:max-w-xl lg:grid-cols-2">
                    <a href="{{ route('usuarios.create') }}" class="app-button app-button-primary w-full">Cadastrar usuário</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="app-button app-button-danger w-full">Sair da conta</button>
                    </form>
                </div>
            </section>

            <section class="mt-4 grid gap-3 lg:grid-cols-2 xl:grid-cols-3">
                @forelse ($usuarios as $usuario)
                    <div class="app-list-card app-card-hover">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0 border-l-4 border-cyan-400 pl-3">
                                <p class="truncate text-base font-black text-white">{{ $usuario->name }}</p>
                                <p class="mt-1 truncate text-xs text-slate-400">Login: {{ $usuario->username }}</p>
                                <p class="mt-1 text-xs text-slate-400">Perfil: {{ ucfirst($usuario->perfil) }}</p>
                            </div>
                            <span class="app-badge">{{ ucfirst($usuario->perfil) }}</span>
                        </div>

                        <div class="mt-3 flex items-center gap-2">
                            <a href="{{ route('usuarios.edit', $usuario) }}" class="app-button app-button-secondary flex-1">Editar</a>
                            <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="app-button app-button-danger w-full" onclick="return confirm('Confirmar exclusão do usuário?')">Excluir</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="app-alert-empty">Nenhum usuário cadastrado ainda.</div>
                @endforelse
            </section>
        </div>
    </div>
</x-app-layout>
