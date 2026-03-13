{{-- Simple Menu Component - Shows menu items with optional submenus --}}
@props(['items' => [], 'user' => null])

@if (count($items) > 0)
    @php
        $visibleItems = [];
        foreach ($items as $item) {
            $hasPermission = true;
            if (!empty($item['permission'])) {
                $hasPermission = Gate::allows($item['permission']);
            }

            if ($hasPermission) {
                // If it has children, check if at least one child is visible
                if (isset($item['items']) && is_array($item['items']) && count($item['items']) > 0) {
                    // We need a recursive check for children visibility
                    $hasVisibleChildren = false;
                    $checkChildren = function($children) use (&$checkChildren) {
                        foreach ($children as $child) {
                            $childPerm = true;
                            if (!empty($child['permission'])) {
                                $childPerm = Gate::allows($child['permission']);
                            }
                            if ($childPerm) {
                                if (!isset($child['items']) || !is_array($child['items']) || count($child['items']) === 0) {
                                    return true;
                                }
                                if ($checkChildren($child['items'])) {
                                    return true;
                                }
                            }
                        }
                        return false;
                    };
                    
                    if ($checkChildren($item['items'])) {
                        $visibleItems[] = $item;
                    }
                } else {
                    $visibleItems[] = $item;
                }
            }
        }
    @endphp

    @foreach ($visibleItems as $itemIndex => $item)
        @php
            // Check if has children
            $hasChildren = isset($item['items']) && is_array($item['items']) && count($item['items']) > 0;
            
            // Build href
            $href = '#';
            if (!empty($item['route'])) {
                if (str_starts_with($item['route'], 'http')) {
                    $href = $item['route'];
                } else {
                    try {
                        $href = route($item['route']);
                    } catch (\Exception $e) {
                        $href = '#';
                    }
                }
            }

            $preventNav = $hasChildren || empty($item['route']);
            $linkHref = $preventNav ? 'javascript:void(0)' : $href;
        @endphp

        <div class="relative group">
            <a href="{{ $linkHref }}" onclick="@if ($preventNav) event.preventDefault(); @endif"
                class="flex items-center justify-between px-3 py-1.5 hover:bg-gray-100 w-full text-left cursor-pointer
                {{ $itemIndex === 0 ? 'rounded-t' : '' }}
                {{ !$hasChildren && $itemIndex === count($visibleItems) - 1 ? 'rounded-b' : '' }}">
                <span>{{ $item['label'] ?? '' }}</span>
                @if ($hasChildren)
                    <svg class="h-3 w-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                @endif
            </a>

            @if ($hasChildren)
                <div class="absolute left-full top-0 bg-white text-gray-800 rounded shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50 text-xs"
                    style="min-width: 180px;">
                    <x-menu-simple :items="$item['items']" :user="$user" />
                </div>
            @endif
        </div>
    @endforeach
@endif

