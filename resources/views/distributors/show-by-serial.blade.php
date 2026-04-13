@extends('layouts.app', ['breadcrumb' => 'Distributor', 'breadcrumbRight' => 'Dashboard->Primary Setup->Distributor->Serial Details'])

@section('content')
    <div class="max-w-full">
        <!-- Card Container -->
        <div class="bg-white rounded shadow-sm border border-gray-200">
            <div class="flex items-center justify-between py-2 px-3 border-b border-gray-200">
                <div class="flex items-center space-x-2">
                    <a href="{{ route('distributors.index') }}" class="btn-secondary" title="Back to distributors list">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        <span>Back to Distributors</span>
                    </a>
                    <div class="text-xs text-gray-600 font-semibold border-l border-gray-300 pl-2">
                        Tally Serial: <span class="text-blue-600 font-bold">{{ $serial }}</span>
                    </div>
                </div>
            </div>

            {{-- <!-- Title/Info Section -->
            <div class="px-3 py-2 bg-gray-50 border-b border-gray-200">
                <div class="text-sm font-semibold text-gray-700">
                    All Distributors for Serial: <span class="text-yellow-600">{{ $serial }}</span>
                </div>
                <div class="text-xs text-gray-600 mt-1">
                    Total: <span class="font-semibold">{{ $distributors->count() }}</span> distributor(s)
                </div>
            </div> --}}

            <!-- Table -->
            <div class="overflow-x-auto" style="max-height: calc(100vh - 263px);">
                <table class="w-full text-xs">
                    <thead class="sticky top-0 bg-white">
                        <tr>
                            <th>SN.</th>
                            <th>Dist. Code</th>
                            <th>Name</th>
                            <th>Principal Company</th>
                            <th>Company Code</th>
                            <th>State</th>
                            <th>Serial No</th>
                            <th>Version</th>
                            <th>Release</th>
                            <th>Expiry</th>
                            <th>Edition</th>
                            <th>Net Id</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($distributors as $sn => $distributor)
                            <tr>
                                <td>{{ $sn + 1 }}</td>
                                <td>{{ $distributor->code }}</td>
                                <td>{{ $distributor->name }}</td>
                                <td>{{ $distributor->company->name ?? $distributor->company_code }}</td>
                                <td>{{ $distributor->company_code }}</td>
                                <td>{{ $distributor->state }}</td>
                                <td>{{ $distributor->tally_serial }}</td>
                                <td>{{ $distributor->tally_version }}</td>
                                <td>{{ $distributor->tally_release }}</td>
                                <td>{{ $distributor->tally_expiry }}</td>
                                <td>{{ $distributor->tally_edition }}</td>
                                <td>{{ $distributor->tally_net_id }}</td>
                                <td>
                                    <div class="flex justify-center space-x-1">
                                        @can('distributor.edit')
                                            <a href="{{ route('distributors.edit', $distributor->id) }}" title="Edit">
                                                <x-icons.edit-circle class="text-primary-0 h-5 w-5" />
                                            </a>
                                        @endcan
                                        @can('distributor.delete')
                                            <button type="button"
                                                onclick="openConfirmModal('Delete Distributor', 'Are you sure you want to delete this distributor?', '{{ route('distributors.destroy', $distributor->id) }}', 'delete', 'delete')"
                                                title="Delete">
                                                <x-icons.trash-circle class="text-red-600 hover:text-red-800 h-5 w-5" />
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="px-3 py-3 text-center text-gray-500 text-xs">
                                    No distributors found for this serial number
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Summary Info -->
            <div class="px-3 py-3 bg-gray-50 border-t border-gray-200 text-xs text-gray-600">
                <div class="flex space-x-4">
                    @php
                        $miscCount = $distributors->where('company_code', 'MISC')->count();
                        $otherCompanyCount = $distributors->where('company_code', '!=', 'MISC')->count();
                    @endphp
                    <div>
                        <span class="font-semibold">MISC:</span>
                        <span class="text-red-600 font-semibold">{{ $miscCount }}</span>
                    </div>
                    <div>
                        <span class="font-semibold">Other Companies:</span>
                        <span class="text-blue-600 font-semibold">{{ $otherCompanyCount }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.confirm-modal')
@endsection
