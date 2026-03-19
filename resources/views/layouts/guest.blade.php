<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

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

<body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="gradient-primary py-3 px-6 sticky top-0 z-50 shadow-lg">
        <div class="max-w-7xl mx-auto">
            <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Logo"
                class="h-[5.25rem] transition hover:scale-105">
        </div>
    </header>

    <!-- Main Content -->
    <div class="flex-1 flex">
        <!-- Left Section - Login Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center py-12 px-4 animate-slide-in">
            <div class="w-full max-w-md">
                <!-- Card Container -->
                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden px-8 py-4 pb-8 backdrop-blur-lg">
                    <!-- Content -->
                    {{ $slot }}
                </div>

                <!-- Footer Link -->
                <div class="text-center mt-6 text-sm text-gray-500">
                    {{ $footer ?? '' }}
                </div>

                <!-- Small Logo -->
                {{-- <div class="flex justify-center mt-8">
                    <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Logo"
                        class="h-8 opacity-75 hover:opacity-100 transition">
                </div> --}}
            </div>
        </div>

        <!-- Right Section - Dummy Image -->
        <div class="hidden lg:flex w-1/2 relative justify-center items-center p-8 overflow-hidden">
            <img src="{{ asset('assets/images/login-side.png') }}" alt="Login Side Image"
                class="w-full h-auto object-cover rounded-2xl transform hover:scale-105 transition duration-300">
        </div>
    </div>

    <!-- Footer -->
    {{-- <footer class="gradient-primary text-white py-2 px-6 text-center text-xs shadow-lg">
        <span>© GARRUDA Technovate Private Limited. All rights reserved - 2025</span>
    </footer> --}}
    <x-app-footer />
    <script>
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
    </script>
</body>

</html>