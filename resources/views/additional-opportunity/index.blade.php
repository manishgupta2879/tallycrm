@extends('layouts.app', ['breadcrumb' => 'Additional Opportunities', 'breadcrumbRight' => 'Dashboard->Additional Opportunity->Additional Oopportunities'])
{{-- @dd('testing'); --}}
@section('content')
    <div class="p-4 max-w-full">


        <!-- Card Container -->
        <div class="bg-white rounded shadow-sm">
            <div class="flex items-center justify-between py-2 px-3 border-b border-gray-200">
                <div class="flex items-center space-x-2">
                    @can('additional-opportunities.create')
                        <a href="{{ route('additional-opportunities.create') }}" class="btn-primary">
                            <i data-lucide="circle-plus" class="w-4 h-4"></i>
                            <span>Add Additional Opportunity</span>
                        </a>
                    @endcan
                </div>
                {{-- ... (search form) ... --}}
                <div class="flex items-center space-x-1">
                    <form method="GET" action="{{ route('additional-opportunities.index') }}"
                        class="flex items-center space-x-1">
                        <div class="flex">
                            <input type="text" name="search" placeholder="Search..." value="{{ request('search', '') }}"
                                class="px-2 py-1.5 border border-gray-300 rounded-l-md text-xs focus:outline-none focus:ring-1 focus:ring-gray-600">
                            <button type="submit"
                                class="bg-gray-200 hover:bg-gray-400 text-dark text-xs py-1.5 px-2.5 rounded-r-md transition border-t border-r border-b border-gray-300 ">
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
                            <th>NAME</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($AdditionalOpportunites as $sn => $additionalOpportunity)
                            <tr class="hover:bg-[#e6e6e6] transition">
                                <td>{{ $AdditionalOpportunites->firstItem() + $sn }}</td>
                                <td>{{ $additionalOpportunity->company_name }}</td>
                                <td>{{ $additionalOpportunity->category->name }}</td>
                                <td>{{ $additionalOpportunity->description }}</td>
                                <td>
                                </td>
                                <td>
                                    <div class="flex justify-center space-x-1">
                                        @can('additional-opportunities.edit')
                                            <a href="{{ route('additional-opportunities.edit', $additionalOpportunity->id) }}"
                                                class="" title="Edit">
                                                <x-icons.edit-circle class="text-primary-0 h-5 w-5" />
                                            </a>
                                        @endcan
                                        @can('additional-opportunities.delete')
                                            @if (true)
                                                <button type="button"
                                                    onclick="openConfirmModal('Delete Additional Opportunity', 'Are you sure you want to delete this additional opportunity? This action cannot be undone.', '{{ route('additional-opportunities.destroy', $additionalOpportunity->id) }}', 'delete', 'delete')"
                                                    title="Delete">
                                                    <x-icons.trash-circle class="text-red-600 hover:text-red-800 h-5 w-5" />
                                                </button>
                                            @else
                                                <span class="cursor-not-allowed opacity-50"
                                                    title="Cannot delete - users assigned">
                                                    <x-icons.trash-circle class="text-gray-400 h-5 w-5" />
                                                </span>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 text-center text-gray-500 text-xs">
                                    No roles found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <x-pagination :currentPage="$AdditionalOpportunites->currentPage()" :totalPages="$AdditionalOpportunites->lastPage()" :totalRecords="$AdditionalOpportunites->total()" :perPage="$AdditionalOpportunites->perPage()"
                baseUrl="{{ route('additional-opportunities.index') }}" />
        </div>
    </div>

    <!-- Include Common Confirm Modal Component -->
    @include('components.confirm-modal')
@endsection
