{{-- Recursive Menu Component - Handles unlimited menu depth levels --}}
@props(['items' => [], 'level' => 1, 'user' => null])

@php
    $user = $user ?? Auth::user();
    $isTopLevel = $level === 1;
@endphp

@if (count($items) > 0)

    @foreach ($items as $itemIndex => $item)
        @php
            // Check direct permissions
            if (isset($item['permission']) && !$user->can($item['permission'])) {
                continue;
            }

            // If it has children, check if at least one is visible
            $hasChildren = isset($item['items']) && is_array($item['items']) && count($item['items']) > 0;
            $visibleChildrenCount = 0;
            
            if ($hasChildren) {
                foreach ($item['items'] as $child) {
                    if (!isset($child['permission']) || $user->can($child['permission'])) {
                        $visibleChildrenCount++;
                    }
                }
            }

            // Hide parent if it has children but none are visible, and it has no route of its own
            if ($hasChildren && $visibleChildrenCount === 0 && empty($item['route'])) {
                continue;
            }

            $hasChildren = $visibleChildrenCount > 0;

            // Build the link href
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
        @endphp

        @if ($isTopLevel)
            <!-- Top Level Menu Item -->
            <div class="relative group">
                <button
                    class="text-white font-medium flex items-center space-x-1 hover:opacity-80 px-2 py-1 rounded transition whitespace-nowrap">
                    <span>{{ $item['label'] ?? '' }}</span>
                    @if ($hasChildren)
                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    @endif
                </button>

                @if ($hasChildren)
                    <!-- Submenu Container -->
                    <div class="absolute left-0 top-full bg-white text-gray-800 rounded shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50 text-xs mt-1"
                        style="min-width: 180px;">
                        <x-menu-recursive :items="$item['items']" :level="$level + 1" :user="$user" />
                    </div>
                @endif
            </div>
        @else
            <!-- Nested Menu Item -->
            <div class="relative menu-item-hover">
                @php
                    $linkHref = $href;
                    $preventNav = $hasChildren || empty($item['route']);
                    if ($preventNav) {
                        $linkHref = 'javascript:void(0)';
                    }
                @endphp
                <a href="{{ $linkHref }}" onclick="@if ($preventNav) event.preventDefault(); @endif" class="flex items-center justify-between px-3 py-1.5 hover:bg-gray-100 w-full text-left cursor-pointer
                                {{ $itemIndex === 0 ? 'rounded-t' : '' }}
                                {{ !$hasChildren && $itemIndex === count($items) - 1 ? 'rounded-b' : '' }}">
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
                    <!-- Next Level Submenu -->
                    <div class="submenu-dropdown absolute left-full top-0 bg-white text-gray-800 rounded shadow-lg opacity-0 invisible transition-all z-50 text-xs"
                        style="min-width: 180px; margin-left: 0;">
                        <x-menu-recursive :items="$item['items']" :level="$level + 1" :user="$user" />
                    </div>
                @endif
            </div>
        @endif
    @endforeach
@endif