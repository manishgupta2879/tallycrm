<!-- Professional App Header - Logo Height | Content Layout -->
<header class="text-white shadow-lg" style="background-color: #276eb6;">
    <div class="flex h-full">

        <!-- Logo Section - Full Height (Left Side) -->
        <div class="flex items-center justify-center h-full flex-shrink-0" style="background-color: #276eb6;">
            <div class="h-full w-20 flex items-center justify-center p-2">
                <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Logo"
                    class="h-full w-full object-contain p-1">
            </div>
        </div>

        <!-- Header Content Section - Right Side -->
        <div class="flex-1 flex flex-col h-full">

            <!-- Top: Company Name Bar -->
            <div class="flex items-center h-10 px-6 space-x-3 border-b border-opacity-10 border-white">
                <div class="text-base font-bold text-white whitespace-nowrap">Welcome
                    {{ Auth::user()->name ?? 'Sony India Pvt Ltd' }}
                </div>
            </div>

            <!-- Bottom: Navigation Menu Bar -->
            <nav class="flex items-center justify-between h-13 text-xs px-6 overflow-visible"
                style="position: relative; z-index: 40;">

                <!-- Left Navigation Menus -->
                <div class="flex items-center space-x-3 overflow-visible nav-dropdown"
                    style="scrollbar-width: none; -ms-overflow-style: none;">

                    @php
                        $menus = config('menu.menus', []);
                        $user = Auth::user();
// dd($menus);
                        // Recursive function to check if any child is visible
                        $checkAnyVisible = function($items) use (&$checkAnyVisible) {
                            foreach ($items as $item) {
                                $hasPermission = true;
                                if (!empty($item['permission'])) {
                                    $hasPermission = Gate::allows($item['permission']);
                                }

                                if ($hasPermission) {
                                    // If it has children, recurse
                                    if (isset($item['items']) && is_array($item['items']) && count($item['items']) > 0) {
                                        if ($checkAnyVisible($item['items'])) {
                                            return true;
                                        }
                                    } else {
                                        // This is a leaf node and user has permission
                                        return true;
                                    }
                                }
                            }
                            return false;
                        };
                    @endphp

                    @foreach($menus as $menu)
                        @php
                            $items = $menu['items'] ?? [];
                            $isMenuVisible = count($items) > 0 && $checkAnyVisible($items);
                        @endphp

                        @if($isMenuVisible)
                            <div class="relative group">
                                <button
                                    class="text-white font-medium flex items-center space-x-1 hover:opacity-80 px-2 py-1 rounded transition whitespace-nowrap">
                                    <span>{{ $menu['label'] }}</span>
                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <!-- Simple Menu List -->
                                <div class="absolute left-0 top-full bg-white text-gray-800 rounded shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50 text-xs mt-1"
                                    style="min-width: 180px;">
                                    <x-menu-simple :items="$items" :user="$user" />
                                </div>
                            </div>
                        @endif
                    @endforeach

                </div>

                <!-- Right Side User Menu -->
                <div class="flex items-center space-x-3 ml-auto flex-shrink-0">
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center space-x-1 hover:opacity-80 px-2 py-2 rounded transition text-xs font-medium whitespace-nowrap">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
                        </svg>
                        <span class="hidden sm:inline">HOME</span>
                    </a>

                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center space-x-1 hover:opacity-80 px-2 py-2 rounded transition text-xs font-medium whitespace-nowrap">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="hidden sm:inline">PROFILE</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                            class="flex items-center space-x-1 hover:opacity-80 px-2 py-2 rounded transition text-xs font-medium whitespace-nowrap">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="hidden sm:inline">LOGOUT</span>
                        </button>
                    </form>
                </div>
            </nav>
        </div>
    </div>
</header>

<!-- Breadcrumb Navigation -->
<div class="border-b border-gray-300 px-3 sm:px-4 lg:px-6 flex items-center justify-between"
    style="background: linear-gradient(#e9d8a6, #efdba4);">
    <span class="text-dark font-bold">{{ $breadcrumb ?? 'Dashboard' }}</span>
    @php
        $trail = explode('->', $breadcrumbRight ?? 'Home');
    @endphp
    <div class="font-medium flex items-center text-xs space-x-1">
        @foreach ($trail as $index => $item)
            @php $item = trim($item); @endphp
            @if ($index > 0)
                <span class="text-gray-400 mx-1">→</span>
            @endif
            @if ($loop->last)
                <span class="text-[#276eb6] font-bold">{{ $item }}</span>
            @else
                <span class="text-gray-600">{{ $item }}</span>
            @endif
        @endforeach
    </div>
</div>