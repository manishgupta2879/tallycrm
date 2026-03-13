<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="{{ asset('assets/js/lucide.min.js') }}"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/logo/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/logo/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/logo/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('assets/images/logo/site.webmanifest') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.ico') }}">
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-[#E5E5E5] flex flex-col">
        <!-- App Header -->
        @include('components.app-header', ['breadcrumb' => $breadcrumb ?? 'Dashboard', 'breadcrumbRight' => $breadcrumbRight ?? 'Home'])

        <!-- Flash Alerts -->
        <div>
            @include('components.flash-alerts')
        </div>

        <!-- Page Content -->
        <main class="flex-1">
            @yield('content')
        </main>

        <!-- Footer -->
        @include('components.app-footer')

        <script>
            lucide.createIcons();

            function setLoading(btn) {
                if (btn.disabled) return;

                btn.disabled = true;
                btn.classList.add('opacity-75', 'cursor-not-allowed');

                const submitText = btn.querySelector('.submit-text');
                const submitLoader = btn.querySelector('.submit-loader');

                if (submitText && submitLoader) {
                    submitText.classList.add('hidden');
                    submitLoader.classList.remove('hidden');
                    submitLoader.innerHTML = '<span class="inline-block animate-spin mr-2">⟳</span>Submitting...';
                } else {
                    const originalText = btn.innerText || 'Submit';
                    btn.innerHTML = `<span class="inline-block animate-spin mr-2">⟳</span>${originalText}...`;
                }

                const form = btn.closest('form');
                if (form) form.submit();
            }
        </script>
    </div>
</body>

</html>