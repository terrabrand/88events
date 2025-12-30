<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} | {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Theme Script -->
    <script type="text/javascript">
        (function() {
            try {
                const root = document.documentElement;
                const savedTheme = localStorage.getItem('theme') || 'light';
                root.setAttribute('data-theme', savedTheme);
            } catch (e) {
                console.warn('Early theme script error:', e);
            }
        })();
    </script>
</head>

<body>
    <!-- Layout wrapper -->
    <div x-data="{ sidebarOpen: false }" class="bg-background text-foreground flex min-h-screen flex-col lg:flex-row">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Layout Container -->
        <div class="flex-1 flex flex-col min-w-0 transition-all duration-300">
            <!-- Header -->
            @include('layouts.header')

            <!-- Content -->
            <main class="mx-auto w-full max-w-[1280px] flex-1 grow px-6 py-8">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="mx-auto w-full max-w-[1280px] px-6 py-3.5 text-sm">
                <div class="flex items-center justify-between gap-3 max-lg:flex-col">
                    <p class="text-base-content text-center">
                        &copy;{{ date('Y') }}
                        <a href="https://terra-brand.com/" class="text-primary">Terrabrand</a>
                        , Made With ❤️ for a better web.
                    </p>
                </div>
            </footer>
        </div>
    </div>

    <!-- FlyonUI JS -->

    @stack('scripts')
</body>

</html>
