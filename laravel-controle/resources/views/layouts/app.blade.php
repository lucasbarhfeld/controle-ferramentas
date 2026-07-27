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
        <link rel="stylesheet" href="{{ asset('vinculos.css') }}?v=2">
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
            class="app-shell mx-auto min-h-screen max-w-xl border-x pb-24 lg:grid lg:max-w-none lg:grid-cols-[280px_minmax(0,1fr)] lg:border-x-0 lg:pb-0"
        >
            @include('layouts.navigation')

            <main class="mx-auto w-full px-5 pb-8 sm:px-6 lg:max-w-none lg:px-10 lg:py-8">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
