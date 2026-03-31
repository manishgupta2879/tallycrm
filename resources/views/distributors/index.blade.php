@extends('layouts.app', ['breadcrumb' => 'Distributor', 'breadcrumbRight' => 'Dashboard->Primary Setup->Distributor'])

@section('content')
    <div class="max-w-full">

        <!-- Card Container -->
        <div class="bg-white rounded shadow-sm border border-gray-200">
            <div class="flex items-center justify-between py-2 px-3 border-b border-gray-200">
                <div class="flex items-center space-x-2">
                    @can('distributor.create')
                        <a href="{{ route('distributors.create') }}" class="btn-primary">
                            <i data-lucide="circle-plus" class="w-4 h-4"></i>
                            <span>Add Distributor</span>
                        </a>
                    @endcan
                </div>
                {{-- ... (search form) ... --}}
                <div class="flex items-center space-x-1">
                    <form method="GET" action="{{ route('distributors.index') }}" class="flex items-center space-x-1">
                        <div class="flex">
                            <input type="text" name="search" placeholder="Search..." value="{{ request('search', '') }}"
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
                <table class="w-full text-xs">
                    <thead class="sticky top-0 bg-white">
                        <tr>
                            <th>SN.</th>
                            <th>Dist. Code</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Principal Company</th>
                            <th>Region</th>
                            <th>State</th>
                            <th>City</th>
                            <th>Created At</th>
                            <th>Last Sync</th>
                            <th>Status</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($distributors as $sn => $distributor)
                            <tr class="hover:bg-[#e6e6e6] transition">
                                <td>{{ $distributors->firstItem() + $sn }}</td>
                                <td>{{ $distributor->code }}</td>
                                <td>{{ $distributor->name }}</td>
                                <td>{{ $distributor->type }}</td>
                                <td>{{ $distributor->company->name ?? $distributor->company_code }}</td>
                                <td>{{ $distributor->geoRegion->name ?? $distributor->region }}</td>
                                <td>{{ $distributor->geoState->name ?? $distributor->state }}</td>
                                <td>{{ $distributor->geoCity->name ?? $distributor->city }}</td>
                                <td>{{ $distributor->created_at->format('d/m/Y') }}</td>
                                <td>{{ $distributor->last_sync_date}}
                                </td>
                                <td>{{ $distributor->status }}</td>
                                <td>
                                    <div class="flex justify-center space-x-1">
                                        @can('distributor.view')
                                            {{-- <a href="{{ route('distributors.tally-details', $distributor->id) }}"
                                                title="Tally Details">
                                                <i data-lucide="clipboard-list" class="text-blue-600 h-5 w-5"></i>
                                            </a> --}}
                                            <a href="{{ route('distributors.tdl-addons', $distributor->id) }}"
                                                title="TDL Addons">
                                                <i data-lucide="puzzle" class="text-orange-600 h-5 w-5"></i>
                                            </a>
                                            <a href="{{ route('distributors.company-features', $distributor->id) }}"
                                                title="Company Features">
                                                <i data-lucide="settings-2" class="text-green-600 h-5 w-5"></i>
                                            </a>
                                        @endcan
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
                                <td colspan="12" class="px-3 text-center text-gray-500 text-xs">No distributors
                                    found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <x-pagination :currentPage="$distributors->currentPage()" :totalPages="$distributors->lastPage()" :totalRecords="$distributors->total()" :perPage="$distributors->perPage()"
                baseUrl="{{ route('distributors.index') }}" />
        </div>
    </div>
    @include('components.confirm-modal')
@endsection
