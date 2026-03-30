@extends('layouts.app', ['breadcrumb' => 'Categories', 'breadcrumbRight' => 'Dashboard->Additional Opportunity->Categories'])
{{-- @dd('testing'); --}}
@section('content')
    <div class="p-4 max-w-full">


        <!-- Card Container -->
        <div class="bg-white rounded shadow-sm">
            <div class="flex items-center justify-between py-2 px-3 border-b border-gray-200">
                <div class="flex items-center space-x-2">
                    @can('categories.create')
                        <a href="{{ route('categories.create') }}" class="btn-primary">
                            <i data-lucide="circle-plus" class="w-4 h-4"></i>
                            <span>Add Category</span>
                        </a>
                    @endcan
                </div>
                {{-- ... (search form) ... --}}
                <div class="flex items-center space-x-1">
                    <form method="GET" action="{{ route('categories.index') }}" class="flex items-center space-x-1">
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
                            {{-- <th>SLUG</th>
                            <th>USERS COUNT</th> --}}
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($categorys as $sn => $category)
                            <tr class="hover:bg-[#e6e6e6] transition">
                                <td>{{ $categorys->firstItem() + $sn }}</td>
                                <td>{{ $category->name }}</td>
                                {{-- <td>{{ $category->slug }}</td>
                                 <td>
                                    <span
                                        class="inline-flex items-center px-1.5 py-0.1 rounded-full text-xs font-medium {{ $role->users_count > 0 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $role->users_count }}
                                    </span>
                                </td> --}}
                                <td>
                                    <div class="flex justify-center space-x-1">
                                        @can('categories.edit')
                                            <a href="{{ route('categories.edit', $category->id) }}" class=""
                                                title="Edit">
                                                <x-icons.edit-circle class="text-primary-0 h-5 w-5" />
                                            </a>
                                        @endcan
                                        @can('categories.delete')
                                            @if (true)
                                                <button type="button"
                                                    onclick="openConfirmModal('Delete Category', 'Are you sure you want to delete this category? This action cannot be undone.', '{{ route('categories.destroy', $category->id) }}', 'delete', 'delete')"
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
            <x-pagination :currentPage="$categorys->currentPage()" :totalPages="$categorys->lastPage()" :totalRecords="$categorys->total()" :perPage="$categorys->perPage()"
                baseUrl="{{ route('categories.index') }}" />
        </div>
    </div>

    <!-- Include Common Confirm Modal Component -->
    @include('components.confirm-modal')
@endsection
