@extends('layouts.app', ['breadcrumb' => 'Company', 'breadcrumbRight' => 'Dashboard -> Primary Setup -> Company'])

@section('content')
    <div class="p-4 max-w-full">


        <!-- Card Container -->
        <div class="bg-white rounded shadow-sm border border-gray-200">
            <div class="flex items-center justify-between py-2 px-3 border-b border-gray-200">
                <div class="flex items-center space-x-2">
                    @can('company.create')
                        <a href="{{ route('companies.create') }}" class="btn-primary">
                            <i data-lucide="circle-plus" class="w-4 h-4"></i>
                            <span>Add Company</span>
                        </a>
                    @endcan
                </div>
                {{-- ... (search form) ... --}}
                <div class="flex items-center space-x-1">
                    <form method="GET" action="{{ route('companies.index') }}" class="flex items-center space-x-1">
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
                            <th>Company Code</th>
                            <th>Name</th>
                            <th>Contact Name</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>Territory</th>
                            <th>Status</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($companies as $sn => $company)
                            <tr class="hover:bg-[#e6e6e6] transition">
                                <td>{{ $companies->firstItem() + $sn }}</td>
                                <td>{{ $company->pid }}</td>
                                <td>{{ $company->name }}</td>
                                <td>{{ $company->contact_name }}</td>
                                <td>{{ $company->mobile }}</td>
                                <td>{{ $company->email }}</td>
                                <td>{{ $company->territory }}</td>
                                <td>{{ $company->status }}</td>
                                <td>
                                    <div class="flex justify-center space-x-1">
                                        @can('company.edit')
                                            <a href="{{ route('companies.edit', $company->id) }}" title="Edit">
                                                <x-icons.edit-circle class="text-primary-0 h-5 w-5" />
                                            </a>
                                        @endcan
                                        @can('company.delete')
                                            <button type="button"
                                                onclick="openConfirmModal('Delete Company', 'Are you sure you want to delete this company?', '{{ route('companies.destroy', $company->id) }}', 'delete', 'delete')"
                                                title="Delete">
                                                <x-icons.trash-circle class="text-red-600 hover:text-red-800 h-5 w-5" />
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-3 py-6 text-center text-gray-500 text-xs">No companies found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <x-pagination :currentPage="$companies->currentPage()" :totalPages="$companies->lastPage()"
                :totalRecords="$companies->total()" :perPage="$companies->perPage()"
                baseUrl="{{ route('companies.index') }}" />
        </div>
    </div>

    @include('components.confirm-modal')
@endsection