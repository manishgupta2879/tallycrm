@extends('layouts.app', ['breadcrumb' => 'Distributor Tally Details', 'breadcrumbRight' => 'Dashboard -> Distributor -> Tally Details'])

@section('content')
    <div class="p-4 max-w-full">
        <div class="bg-white rounded shadow-sm border border-gray-200">
            <div class="flex items-center justify-between py-2 px-3 border-b border-gray-200">
                <h6 class="text-sm font-bold">Tally Details for {{ $distributor->name }} (Serial: {{ $distributor->tally_serial }})</h6>
                <div class="flex items-center space-x-1">
                    <a href="{{ route('distributors.edit', $distributor->id) }}" class="btn-secondary">
                        <i data-lucide="edit" class="h-4 w-4"></i> Edit Distributor
                    </a>
                    <a href="{{ route('distributors.index') }}" class="btn-secondary">
                        <i data-lucide="list" class="h-4 w-4"></i> Distributor List
                    </a>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr>
                            <th>SN.</th>
                            <th>Version</th>
                            <th>Release</th>
                            <th>Edition</th>
                            <th>Account ID</th>
                            <th>TSS Expiry</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($logs as $sn => $log)
                            <tr class="hover:bg-gray-50 transition">
                                <td>{{ $logs->firstItem() + $sn }}</td>
                                <td>{{ $log->tally_version }}</td>
                                <td>{{ $log->tally_release }}</td>
                                <td>{{ $log->tally_edition }}</td>
                                <td>{{ $log->account_id }}</td>
                                <td>{{ $log->tss_expiry_date }}</td>
                                <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-3 py-6 text-center text-gray-500">No logs found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-3">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
@endsection
