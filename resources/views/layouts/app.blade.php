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

    <!-- Flatpickr (Datepicker) -->
    <link rel="stylesheet" href="{{ asset('assets/css/flatpickr.min.css') }}">
    <script src="{{ asset('assets/js/flatpickr.js') }}"></script>

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
        @include('components.app-header', [
            'breadcrumb' => $breadcrumb ?? 'Dashboard',
            'breadcrumbRight' => $breadcrumbRight ?? 'Home',
        ])

        <!-- Flash Alerts -->
        <div>
            @include('components.flash-alerts')
        </div>

        <!-- Page Content -->
        <main class="flex-1 p-4">
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
                    submitLoader.innerHTML = `<svg class="animate-spin h-4 w-4 text-white inline-block mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>Submitting...`;
                } else {
                    const originalText = btn.innerText || 'Submit';
                    btn.innerHTML = `<svg class="animate-spin h-4 w-4 text-white inline-block mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>${originalText}...`;
                }

                const form = btn.closest('form');
                if (form) form.submit();
            }

            window.addEventListener("scroll", () => {
                const stickyHeader = document.getElementById("stickyHeader");
                if (window.scrollY > 10) {
                    stickyHeader.classList.add("shadow-md");
                } else {
                    stickyHeader.classList.remove("shadow-md");
                }
            });
        </script>
    </div>
</body>

</html>
