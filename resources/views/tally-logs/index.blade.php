@extends('layouts.app', ['breadcrumb' => 'Tally Log', 'breadcrumbRight' => 'Dashboard -> Other Admin -> Tally Log'])

@section('content')
    <div class="p-4 max-w-full">
        <!-- Card Container -->
        <div class="bg-white rounded shadow-sm border border-gray-200">
            <div class="flex items-center justify-between py-2 px-3 border-b border-gray-200">
                <div class="flex items-center space-x-2">
                    <h2 class="text-sm font-bold text-gray-700">Tally Generation Logs</h2>
                </div>
                <div class="flex items-center space-x-1">
                    <form method="GET" action="{{ route('tally-logs.index') }}" class="flex items-center space-x-1">
                        <div class="flex">
                            <input type="text" name="search" placeholder="Search PID/Serial..." value="{{ request('search', '') }}"
                                class="px-2 py-1.5 border border-gray-300 rounded-l-md text-xs focus:outline-none focus:ring-1 focus:ring-gray-600" />
                            <button type="submit"
                                class="bg-gray-200 hover:bg-gray-400 text-dark text-xs py-1.5 px-2.5 rounded-r-md transition border-t border-r border-b border-gray-300">
                                <i data-lucide="search" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto" style="max-height: calc(100vh - 263px);">
                <table class="w-full text-left text-xs">
                    <thead class="sticky top-0 bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-3 py-2 font-bold text-gray-700">SN.</th>
                            <th class="px-3 py-2 font-bold text-gray-700">PID</th>
                            <th class="px-3 py-2 font-bold text-gray-700">Dist. ID</th>
                            <th class="px-3 py-2 font-bold text-gray-700">Serial No.</th>
                            <th class="px-3 py-2 font-bold text-gray-700">Version</th>
                            <th class="px-3 py-2 font-bold text-gray-700">Release</th>
                            <th class="px-3 py-2 font-bold text-gray-700">Edition</th>
                            <th class="px-3 py-2 font-bold text-gray-700">Account ID</th>
                            <th class="px-3 py-2 font-bold text-gray-700">TSS Expiry</th>
                            <th class="px-3 py-2 font-bold text-gray-700">Generated At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($tallyLogs as $sn => $log)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-3 py-2">{{ $tallyLogs->firstItem() + $sn }}</td>
                                <td class="px-3 py-2 font-medium text-blue-600">{{ $log->pid }}</td>
                                <td class="px-3 py-2">{{ $log->distributor_id }}</td>
                                <td class="px-3 py-2 font-semibold">{{ $log->tally_serial_no }}</td>
                                <td class="px-3 py-2">{{ $log->tally_version }}</td>
                                <td class="px-3 py-2">{{ $log->tally_release }}</td>
                                <td class="px-3 py-2">{{ $log->tally_edition }}</td>
                                <td class="px-3 py-2">{{ $log->account_id }}</td>
                                <td class="px-3 py-2">{{ $log->tss_expiry_date }}</td>
                                <td class="px-3 py-2">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-3 py-6 text-center text-gray-500 text-xs">No Tally logs found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <x-pagination :currentPage="$tallyLogs->currentPage()" :totalPages="$tallyLogs->lastPage()"
                :totalRecords="$tallyLogs->total()" :perPage="$tallyLogs->perPage()"
                baseUrl="{{ route('tally-logs.index') }}" />
        </div>
    </div>
@endsection
