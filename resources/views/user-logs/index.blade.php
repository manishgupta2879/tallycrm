@extends('layouts.app', ['breadcrumb' => 'User Logs', 'breadcrumbRight' => 'System -> User Logs'])

@section('content')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <div class="p-4 max-w-full flex space-x-4">
        <!-- Filter Sidebar -->
        <div id="filterSidebar"
            class="w-80 flex-shrink-0 transition-all duration-300 {{ request('show_filters', 'true') === 'true' ? '' : 'hidden' }}">
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
                        <div class="col-span-1">
                            <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">User</label>
                            <select name="user_id"
                                class="w-full text-xs border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 py-1 px-2 select2-basic">
                                <option value="">User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
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
                                        {{ $y }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-1 flex items-end">
                            <x-primary-button type="submit" class="w-full justify-center py-1.5 h-[30px]">
                                <i data-lucide="search" class="w-3.5 h-3.5 mr-1"></i> Search
                            </x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 min-w-0">
            <div class="bg-white rounded shadow-sm">
                <!-- Toolbar -->
                <div class="flex items-center justify-between py-2 px-3 border-b border-gray-200">
                    <div class="flex items-center space-x-1">
                        <button onclick="toggleFilters()"
                            class="bg-primary-0 hover:opacity-90 text-white rounded px-2 py-1.5 transition shadow-sm"
                            title="Toggle Filters">
                            <i data-lucide="chevron-left" id="toggleIcon"
                                class="w-4 h-4 {{ request('show_filters', 'true') === 'true' ? '' : 'rotate-180' }}"></i>
                        </button>
                        @can('activity-log.export')
                            <x-primary-outline-button onclick="exportExcel()">
                                <i data-lucide="file-down" class="w-4 h-4"></i> Export
                            </x-primary-outline-button>
                        @endcan
                    </div>
                    <div class="flex items-center space-x-1">
                        <div class="relative inline-block group">
                            <x-primary-button>More Links <i data-lucide="chevron-down"
                                    class="w-4 h-4"></i></x-primary-button>
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
                </div>

                <!-- Table Container -->
                <div class="overflow-x-auto" style="max-height: calc(100vh - 263px);">
                    <table class="w-full text-xs">
                        <thead class="sticky top-0 bg-white z-10">
                            <tr>
                                <th>SN.</th>
                                <th>LOGIN AT</th>
                                <th>USER</th>
                                <th>ACTIVITY</th>
                                <th>LAST ACTIVITY AT</th>
                                <th>LOGOUT</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($logs as $sn => $log)
                                <tr class="hover:bg-[#e6e6e6] transition group">
                                    <td>{{ $logs->firstItem() + $sn }}</td>
                                    <td class="whitespace-nowrap">{{ $log->log_in ? $log->log_in->format('d/m/Y H:i:s') : '-' }}
                                    </td>
                                    <td>{{ $log->user->name ?? 'N/A' }}</td>
                                    <td class="max-w-md">
                                        @php
                                            $details = unserialize($log->detail) ?: [];
                                            $activities = collect($details)
                                                ->map(function ($item) {
                                                    return $item['detail'] ?? '';
                                                })
                                                ->filter()
                                                ->take(10)
                                                ->implode(' | ');
                                            if (count($details) > 10) {
                                                $activities .= ' ...';
                                            }
                                        @endphp
                                        {{ $activities ?: 'No details recorded' }}
                                    </td>
                                    <td>{{ $log->last_activity ? $log->last_activity->format('d/m/Y H:i:s') : '-' }}</td>
                                    <td>
                                        @if ($log->log_out)
                                            {{ $log->log_out->format('d/m/Y H:i:s') }}
                                        @elseif($log->date && $log->date->isToday())
                                            <span class="text-gray-400">--</span>
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td class="text-center px-0">
                                        <div class="flex justify-center">
                                            <a href="{{ route('user-logs.show', $log->id) }}"
                                                class="p-1 hover:bg-gray-200 rounded-full transition text-gray-400 group-hover:text-blue-600"
                                                title="View Timeline">
                                                <i data-lucide="eye" class="w-5 h-5"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-3 py-6 text-center text-gray-500 italic">
                                        No activity logs found matching your criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($logs->hasPages())
                    <x-pagination :currentPage="$logs->currentPage()" :totalPages="$logs->lastPage()" :totalRecords="$logs->total()" :perPage="$logs->perPage()"
                        baseUrl="{{ route('user-logs.index') }}" />
                @endif
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2
            $('.select2-basic').select2({
                allowClear: true,
                width: '100%',
                placeholder: 'User',
                containerCssClass: 'text-xs',
                selectionCssClass: 'text-xs'
            });

            // Initialize Lucide icons if they aren't already
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        function toggleFilters() {
            const sidebar = document.getElementById('filterSidebar');
            const icon = document.getElementById('toggleIcon');
            const input = document.getElementById('showFiltersInput');

            if (sidebar.classList.contains('hidden')) {
                sidebar.classList.remove('hidden');
                icon.classList.remove('rotate-180');
                input.value = 'true';
            } else {
                sidebar.classList.add('hidden');
                icon.classList.add('rotate-180');
                input.value = 'false';
            }
        }

        function exportExcel() {
            alert('Exporting to Excel...');
        }
    </script>
@endsection