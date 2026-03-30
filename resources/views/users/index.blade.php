@extends('layouts.app', ['breadcrumb' => 'User', 'breadcrumbRight' => 'Dashboard->Primary Setup->User'])

@section('content')
    <div class="max-w-full">



        <!-- Card Container -->
        <div class="bg-white rounded shadow-sm">
            <div class="flex items-center justify-between py-2 px-3 border-b border-gray-200">
                <div class="flex items-center space-x-2">
                    @can('users.create')
                        <a href="{{ route('users.create') }}" class="btn-primary">
                            <i data-lucide="circle-plus" class="w-4 h-4"></i>
                            <span>Add User</span>
                        </a>
                    @endcan
                    {{-- Export button commented out --}}
                    {{-- @can('users.export')
                        <x-primary-outline-button onclick="exportExcel()">
                            <i data-lucide="file-down" class="w-4 h-4"></i> Excel
                        </x-primary-outline-button>
                    @endcan --}}
                </div>
                {{-- ... (search form) ... --}}
                <div class="flex items-center space-x-1">
                    <form method="GET" action="{{ route('users.index') }}" class="flex items-center space-x-1">
                        <div class="flex">
                            <input type="text" name="search" placeholder="Search..." value="{{ request('search', '') }}"
                                class="px-2 py-1.5 border border-gray-300 rounded-l-md text-xs focus:outline-none focus:ring-1 focus:ring-gray-600">
                            <button type="submit"
                                class="bg-gray-200 hover:bg-gray-400 text-dark text-xs py-1.5 px-2.5 rounded-r-md transition border-t border-r border-b border-gray-300 ">
                                <i data-lucide="search" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </form>
                    <div class="relative inline-block group">

                        <!-- Button -->
                        <x-primary-button>More Links <i data-lucide="chevron-down" class="w-4 h-4"></i></x-primary-button>

                        <!-- Dropdown -->
                        <div
                            class="absolute z-10 right-0 mt-2 w-40 bg-white border border-gray-200 shadow-lg hidden group-hover:block text-sm">
                            <a href="#" class="block px-2 py-1 hover:bg-gray-100">Company</a>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto" style="max-height: calc(100vh - 263px);">
                <table class="w-full text-xs">
                    <thead class="sticky top-0 bg-white">
                        <tr>
                            <th>SN.</th>
                            <th>NAME</th>
                            <th>EMAIL</th>
                            <th>Role</th>
                            <th>Access Companies</th>
                            <th>STATUS</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($users as $sn => $user)
                            <tr class="hover:bg-[#e6e6e6] transition">
                                <td>{{ $users->firstItem() + $sn }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role?->name }}</td>
                                <td>
                                    @php
                                        $companyNames = $user->companies->pluck('name')->implode(', ');
                                    @endphp
                                    {{ $companyNames ?: 'None' }}
                                </td>
                                <td>{{ $user->status ? 'Active' : 'Inactive' }}</td>
                                <td>
                                    <div class="flex justify-center space-x-1">
                                        @can('users.edit')
                                            <a href="{{ route('users.edit', $user->id) }}" class="" title="Edit">
                                                <x-icons.edit-circle class="text-primary-0 h-5 w-5" />
                                            </a>
                                        @endcan
                                        @can('users.delete')
                                            <button type="button"
                                                onclick="openConfirmModal('Delete User', 'Are you sure you want to delete this user? This action cannot be undone.', '{{ route('users.destroy', $user->id) }}', 'delete', 'delete')"
                                                title="Delete">
                                                <x-icons.trash-circle class="text-red-600 hover:text-red-800 h-5 w-5" />
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-3 text-center text-gray-500 text-xs">
                                    No users found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <x-pagination :currentPage="$users->currentPage()" :totalPages="$users->lastPage()" :totalRecords="$users->total()" :perPage="$users->perPage()"
                baseUrl="{{ route('users.index') }}" />
        </div>
    </div>

    <!-- Include Common Confirm Modal Component -->
    @include('components.confirm-modal')

    <script>
        function exportExcel() {
            alert('Export to Excel feature coming soon!');
        }
    </script>
@endsection
