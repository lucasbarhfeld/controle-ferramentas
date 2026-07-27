<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#111820">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-title" content="Ferramentas">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

        <title>Controle de ferramentas</title>

        <script>
            const savedTheme = localStorage.getItem('theme') || 'dark';
            document.documentElement.dataset.theme = savedTheme;
        </script>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
        <link rel="icon" type="image/svg+xml" href="{{ asset('pwa-icon.svg') }}">
        <link rel="apple-touch-icon" href="{{ asset('pwa-icon-192.png') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div
            x-data="{
                theme: localStorage.getItem('theme') || 'dark',
                toggleTheme() {
                    this.theme = this.theme === 'dark' ? 'light' : 'dark';
                    localStorage.setItem('theme', this.theme);
                    document.documentElement.dataset.theme = this.theme;
                }
            }"
            x-init="document.documentElement.dataset.theme = theme"
            class="app-shell app-guest-shell flex min-h-screen flex-col items-center justify-center px-4 py-8 lg:max-w-none lg:border-x-0"
        >
            <div class="mb-6 flex w-full max-w-xl items-center justify-between gap-3 lg:max-w-6xl">
                <a href="/" class="inline-flex min-w-0 items-center gap-3">
                    <img src="{{ asset('logo-lippel.svg') }}" alt="Lippel" class="h-10 w-auto shrink-0">
                    <span class="truncate text-xs font-black uppercase tracking-[0.16em] app-muted">Controle de ferramentas</span>
                </a>

                <button type="button" class="app-theme-toggle" @click="toggleTheme()" aria-label="Alternar tema">
                    <svg x-show="theme === 'dark'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.4-6.4-1.4 1.4M7 17l-1.4 1.4m0-12.8L7 7m10 10 1.4 1.4" />
                        <circle cx="12" cy="12" r="4" />
                    </svg>
                    <svg x-show="theme === 'light'" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.8A8 8 0 1 1 11.2 3 6.2 6.2 0 0 0 21 12.8z" />
                    </svg>
                </button>
            </div>

            <div class="w-full max-w-xl lg:max-w-6xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
