<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Authentication Required Label -->
    {{-- <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-1">Welcome Back</h1>
        <p class="text-sm text-gray-500">Sign in to your account to continue</p>
    </div> --}}

    <form method="POST" action="{{ route('login') }}" class="space-y-4" id="loginForm" novalidate>
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
            <x-text-input id="email"
                class="block w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#276eb6] focus:border-transparent transition"
                type="email" name="email" :value="old('email')" placeholder="you@example.com" required autofocus
                autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="text-xs mt-1" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <x-text-input id="password"
                class="block w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#276eb6] focus:border-transparent transition"
                type="password" name="password" placeholder="••••••••" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="text-xs mt-1" />
        </div>



        <!-- Remember Me and Forgot Password Row -->
        <div class="flex items-center justify-between text-sm">
            <label for="remember_me" class="flex items-center gap-2 cursor-pointer">
                <input id="remember_me" type="checkbox" class="w-4 h-4 rounded border-gray-300 text-[#276eb6]"
                    name="remember">
                <span class="text-gray-700">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-[#276eb6] hover:text-blue-700 font-medium" href="{{ route('password.request') }}">
                    Forgot password?
                </a>
            @endif
        </div>

        <div class="pt-2">
            <button type="submit" id="submitBtn" onclick="setLoading(this)"
                class="w-full bg-[#276eb6] hover:bg-blue-800 text-white font-semibold py-2.5 px-4 rounded-lg text-sm transition duration-200 transform hover:scale-105 shadow-md">
                Sign In
            </button>
        </div>
    </form>

    {{-- <x-slot name="footer"></x-slot> --}}

</x-guest-layout>