@php
    $isAdmin = auth()->user()?->isAdmin();

    $navItems = [
        [
            'label' => 'Painel',
            'route' => 'dashboard',
            'active' => request()->routeIs('dashboard'),
            'icon' => 'M4 5h6v6H4zM14 5h6v6h-6zM4 15h6v4H4zM14 15h6v4h-6z',
        ],
        [
            'label' => 'Ferramentas',
            'route' => 'equipamentos.index',
            'active' => request()->routeIs('equipamentos.*'),
            'icon' => 'M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.8-3.8a6 6 0 0 1-7.9 7.9l-6.9 6.9a2.1 2.1 0 0 1-3-3l6.9-6.9a6 6 0 0 1 7.9-7.9l-3.8 3.8z',
        ],
        [
            'label' => 'Histórico',
            'route' => 'calibracoes.index',
            'active' => request()->routeIs('calibracoes.index'),
            'icon' => 'M7 7h10M7 12h10M7 17h6M5 4h14v16H5z',
        ],
        $isAdmin
            ? [
                'label' => 'Usuários',
                'route' => 'usuarios.index',
                'active' => request()->routeIs('usuarios.*') || request()->routeIs('centros-custo.*'),
                'icon' => 'M8 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM2 20a6 6 0 0 1 12 0M17 10a2.5 2.5 0 1 0 0-5M15 20a5 5 0 0 1 7 0',
            ]
            : [
                'label' => 'Minha Área',
                'route' => 'me.index',
                'active' => request()->routeIs('me.*'),
                'icon' => 'M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM4 20a8 8 0 0 1 16 0',
            ],
    ];
@endphp

<nav class="app-nav lg:contents">
    <div class="mx-auto max-w-xl px-5 pb-3 pt-5 sm:px-6 lg:hidden">
        <div class="app-nav-border flex items-center justify-between gap-3 border-b pb-4">
            <a href="{{ route('dashboard') }}" class="flex min-w-0 items-center gap-3">
                <img src="{{ asset('logo-lippel.svg') }}" alt="Lippel" class="h-9 w-auto shrink-0">
                <span class="min-w-0 truncate text-[11px] font-black uppercase tracking-[0.16em] app-muted">Controle de ferramentas</span>
            </a>

            <button type="button" class="app-theme-toggle shrink-0" @click="toggleTheme()" aria-label="Alternar tema">
                <svg x-show="theme === 'dark'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.4-6.4-1.4 1.4M7 17l-1.4 1.4m0-12.8L7 7m10 10 1.4 1.4" />
                    <circle cx="12" cy="12" r="4" />
                </svg>
                <svg x-show="theme === 'light'" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.8A8 8 0 1 1 11.2 3 6.2 6.2 0 0 0 21 12.8z" />
                </svg>
            </button>

            <div class="hidden items-center gap-2 sm:flex lg:hidden">
                @foreach ($navItems as $item)
                    <a
                        href="{{ route($item['route']) }}"
                        class="rounded-full px-3 py-2 text-xs font-bold uppercase tracking-[0.12em] ring-1 transition {{ $item['active'] ? 'app-button-primary' : 'app-button-secondary' }}"
                    >
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <aside class="app-desktop-sidebar hidden lg:flex">
        <div class="flex min-h-0 flex-1 flex-col gap-7">
            <a href="{{ route('dashboard') }}" class="app-desktop-brand">
                <img src="{{ asset('logo-lippel.svg') }}" alt="Lippel" class="h-11 w-auto">
                <span>Controle de ferramentas</span>
            </a>

            <a href="{{ route('calibracoes.create') }}" class="app-desktop-action {{ request()->routeIs('calibracoes.create') ? 'is-active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5" />
                </svg>
                <span>Nova calibração</span>
            </a>

            <div class="space-y-2">
                <p class="px-3 text-[10px] font-black uppercase tracking-[0.24em] app-muted">Navegação</p>
                @foreach ($navItems as $item)
                    <a href="{{ route($item['route']) }}" class="app-desktop-nav-link {{ $item['active'] ? 'is-active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                        </svg>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="mt-auto space-y-3">
            <button type="button" class="app-desktop-theme-toggle" @click="toggleTheme()">
                <svg x-show="theme === 'dark'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.4-6.4-1.4 1.4M7 17l-1.4 1.4m0-12.8L7 7m10 10 1.4 1.4" />
                    <circle cx="12" cy="12" r="4" />
                </svg>
                <svg x-show="theme === 'light'" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.8A8 8 0 1 1 11.2 3 6.2 6.2 0 0 0 21 12.8z" />
                </svg>
                <span x-text="theme === 'dark' ? 'Modo claro' : 'Modo escuro'"></span>
            </button>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="app-desktop-logout">Sair da conta</button>
            </form>
        </div>
    </aside>

    <div class="app-bottom-nav lg:hidden">
        <div class="app-bottom-nav-bar">
            @foreach (array_slice($navItems, 0, 2) as $item)
                <a href="{{ route($item['route']) }}" class="app-bottom-nav-link {{ $item['active'] ? 'is-active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                    </svg>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach

            <a href="{{ route('calibracoes.create') }}" class="app-bottom-nav-action {{ request()->routeIs('calibracoes.create') ? 'is-active' : '' }}" aria-label="Nova calibração">
                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5" />
                </svg>
            </a>

            @foreach (array_slice($navItems, 2) as $item)
                <a href="{{ route($item['route']) }}" class="app-bottom-nav-link {{ $item['active'] ? 'is-active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                    </svg>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</nav>
