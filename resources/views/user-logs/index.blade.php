@extends('layouts.app', ['breadcrumb' => 'User Logs', 'breadcrumbRight' => 'System->User Logs'])

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <div class="p-4 max-w-full flex space-x-4">

        {{-- ─── Filter Sidebar ──────────────────────────────────────────── --}}
        <div id="filterSidebar"
            class="w-72 flex-shrink-0 transition-all duration-300 {{ request('show_filters', 'true') === 'true' ? '' : 'hidden' }}">
            <div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-3 py-2 border-b border-gray-200 flex justify-between items-center">
                    <span class="font-bold text-gray-700 text-xs">Filters</span>
                    <button onclick="toggleFilters()" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                <form action="{{ route('user-logs.index') }}" method="GET" class="p-3">
                    <input type="hidden" name="show_filters" id="showFiltersInput"
                        value="{{ request('show_filters', 'true') }}">
                    <div class="grid grid-cols-2 gap-2">
                        <div class="col-span-2">
                            <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">User</label>
                            <select name="user_id"
                                class="w-full text-xs border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 py-1 px-2 select2-basic">
                                <option value="">All Users</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Month</label>
                            <select name="month"
                                class="w-full text-xs border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 py-1 px-2">
                                <option value="">Month</option>
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                        {{ date('M', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Year</label>
                            <select name="year"
                                class="w-full text-xs border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 py-1 px-2">
                                <option value="">Year</option>
                                @foreach (range(date('Y'), date('Y') - 5) as $y)
                                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                        {{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2 mt-1">
                            <x-primary-button type="submit" class="w-full justify-center py-1.5 h-[30px]">
                                <i data-lucide="search" class="w-3.5 h-3.5 mr-1"></i> Search
                            </x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- ─── Main Table ───────────────────────────────────────────────── --}}
        <div class="flex-1 min-w-0">
            <div class="bg-white rounded shadow-sm border border-gray-200">

                {{-- Toolbar --}}
                <div class="flex items-center justify-between py-2 px-3 border-b border-gray-200">
                    <div class="flex items-center space-x-1">
                        <button onclick="toggleFilters()"
                            class="bg-primary-0 hover:opacity-90 text-white rounded px-2 py-1.5 transition shadow-sm"
                            title="Toggle Filters">
                            <i data-lucide="chevron-left" id="toggleIcon"
                                class="w-4 h-4 {{ request('show_filters', 'true') === 'true' ? '' : 'rotate-180' }}"></i>
                        </button>
                        {{-- Export button commented out --}}
                        {{-- @can('activity-log.export')
                            <x-primary-outline-button onclick="exportExcel()">
                                <i data-lucide="file-down" class="w-4 h-4"></i> Export
                            </x-primary-outline-button>
                        @endcan --}}
                    </div>
                    <div class="relative inline-block group">
                        <x-primary-button>More Links <i data-lucide="chevron-down" class="w-4 h-4"></i></x-primary-button>
                        <div
                            class="absolute z-50 right-0 mt-2 w-40 bg-white border border-gray-200 shadow-lg hidden group-hover:block text-xs">
                            @can('users.view')
                                <a href="{{ route('users.index') }}" class="block px-3 py-2 hover:bg-gray-100">User List</a>
                            @endcan
                            @can('roles.view')
                                <a href="{{ route('roles.index') }}" class="block px-3 py-2 hover:bg-gray-100">Roles</a>
                            @endcan
                        </div>
                    </div>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto" style="max-height: calc(100vh - 263px);">
                    <table class="w-full text-xs">
                        <thead class="sticky top-0 bg-white z-10 border-b border-gray-200">
                            <tr class="text-gray-500 uppercase text-[10px] font-semibold tracking-wide">
                                <th class="px-3 py-2 text-left w-8">#</th>
                                <th class="px-3 py-2 text-left whitespace-nowrap">Login At</th>
                                <th class="px-3 py-2 text-left">User</th>
                                <th class="px-3 py-2 text-left">Summary</th>
                                <th class="px-3 py-2 text-left whitespace-nowrap">Last Active</th>
                                <th class="px-3 py-2 text-left whitespace-nowrap">Logout</th>
                                <th class="px-3 py-2 text-center w-12">View</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($logs as $sn => $log)
                                @php
                                    $details = unserialize($log->detail) ?: [];
                                    $total = count($details);
                                    $counts = collect($details)->groupBy('action')->map(fn($g) => $g->count());
                                    $preview = collect($details)->first(
                                        fn($d) => !in_array($d['action'] ?? '', ['LOGIN', 'LOGOUT']),
                                    );
                                @endphp
                                <tr class="hover:bg-gray-50 transition group align-top">

                                    {{-- # --}}
                                    <td class="px-3 py-2.5 text-gray-400">{{ $logs->firstItem() + $sn }}</td>

                                    {{-- Login At --}}
                                    <td class="px-3 py-2.5 whitespace-nowrap">
                                        @if ($log->log_in)
                                            <div class="text-gray-700">{{ $log->log_in->format('d/m/Y') }}</div>
                                            <div class="text-[10px] text-gray-400">{{ $log->log_in->format('H:i:s') }}
                                            </div>
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>

                                    {{-- User --}}
                                    <td class="px-3 py-2.5">
                                        <div class="font-medium text-gray-800">{{ $log->user->name ?? 'N/A' }}</div>
                                        <div class="text-[10px] text-gray-400">{{ $log->user->email ?? '' }}</div>
                                    </td>

                                    {{-- Summary --}}
                                    <td class="px-3 py-2.5">
                                        @if ($total === 0)
                                            <span class="text-[10px] text-gray-300">No activity</span>
                                        @else
                                            <div class="flex flex-wrap items-center gap-1">
                                                <span
                                                    class="px-1.5 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-600">{{ $total }}
                                                    actions</span>

                                                @if (isset($counts['LOGIN']))
                                                    <span
                                                        class="px-1.5 py-0.5 rounded text-[10px] font-medium bg-blue-50 text-blue-600">{{ $counts['LOGIN'] }}
                                                        login</span>
                                                @endif
                                                @if (isset($counts['CREATE']))
                                                    <span
                                                        class="px-1.5 py-0.5 rounded text-[10px] font-medium bg-green-50 text-green-600">{{ $counts['CREATE'] }}
                                                        create</span>
                                                @endif
                                                @if (isset($counts['UPDATE']))
                                                    <span
                                                        class="px-1.5 py-0.5 rounded text-[10px] font-medium bg-orange-50 text-orange-500">{{ $counts['UPDATE'] }}
                                                        update</span>
                                                @endif
                                                @if (isset($counts['DELETE']))
                                                    <span
                                                        class="px-1.5 py-0.5 rounded text-[10px] font-medium bg-red-50 text-red-600">{{ $counts['DELETE'] }}
                                                        delete</span>
                                                @endif
                                            </div>
                                            @if ($preview)
                                                <div class="text-[10px] text-gray-400 mt-0.5 truncate max-w-sm"
                                                    title="{{ $preview['detail'] ?? '' }}">
                                                    {{ Str::limit($preview['detail'] ?? '', 70) }}
                                                </div>
                                            @endif
                                        @endif
                                    </td>

                                    {{-- Last Active --}}
                                    <td class="px-3 py-2.5 whitespace-nowrap">
                                        @if ($log->last_activity)
                                            <div class="text-gray-700">{{ $log->last_activity->format('d/m/Y') }}</div>
                                            <div class="text-[10px] text-gray-400">
                                                {{ $log->last_activity->format('H:i:s') }}</div>
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>

                                    {{-- Logout --}}
                                    <td class="px-3 py-2.5 whitespace-nowrap">
                                        @if ($log->log_out)
                                            <div class="text-gray-700">{{ $log->log_out->format('d/m/Y') }}</div>
                                            <div class="text-[10px] text-gray-400">{{ $log->log_out->format('H:i:s') }}
                                            </div>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>

                                    {{-- View --}}
                                    <td class="px-3 py-2.5 text-center">
                                        <a href="{{ route('user-logs.show', $log->id) }}"
                                            class="inline-flex items-center justify-center w-7 h-7 rounded-full hover:bg-gray-100 text-gray-400 group-hover:text-blue-600 transition"
                                            title="View Timeline">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-3 text-center text-gray-400 text-xs italic">
                                        No activity logs found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($logs->hasPages())
                    <x-pagination :currentPage="$logs->currentPage()" :totalPages="$logs->lastPage()" :totalRecords="$logs->total()" :perPage="$logs->perPage()"
                        baseUrl="{{ route('user-logs.index') }}" />
                @endif
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.select2-basic').select2({
                allowClear: true,
                width: '100%',
                placeholder: 'All Users'
            });
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });

        function toggleFilters() {
            const sidebar = document.getElementById('filterSidebar');
            const icon = document.getElementById('toggleIcon');
            const input = document.getElementById('showFiltersInput');
            const hidden = sidebar.classList.contains('hidden');
            sidebar.classList.toggle('hidden', !hidden);
            icon.classList.toggle('rotate-180', !hidden);
            input.value = hidden ? 'true' : 'false';
        }

        function exportExcel() {
            alert('Exporting to Excel...');
        }
    </script>
@endsection
