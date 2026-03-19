@extends('layouts.app', ['breadcrumb' => 'User', 'breadcrumbRight' => 'Dashboard -> Primary Setup -> User'])

@section('content')
    <div class="p-4 max-w-full">
        <div class="bg-white rounded shadow-sm border border-gray-200">
            <div class="flex items-center justify-between py-2 px-3 border-b border-gray-200">
                <h6>Edit User</h6>
                <div class="flex items-center space-x-1">
                    <a href="{{ route('users.index') }}" class="btn-secondary">
                        <i data-lucide="list" class="h-4 w-4"></i> User List
                    </a>
                    <div class="relative inline-block group">
                        <!-- Button -->
                        <x-primary-button type="button">More Links <i data-lucide="chevron-down"
                                class="w-4 h-4"></i></x-primary-button>

                        <!-- Dropdown -->
                        <div
                            class="absolute z-10 right-0 mt-2 w-40 bg-white border border-gray-200 shadow-lg hidden group-hover:block text-sm">
                            <a href="#" class="block px-2 py-1 hover:bg-gray-100">Company</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4">
                <!-- Form -->
                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Row 1: Name, Email, Password, Role -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-3">
                        <!-- Name -->
                        <div>
                            <label class="block text-gray-700 font-semibold text-xs mb-1">
                                Name<span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                placeholder="Full name">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Email -->
                        <div>
                            <label class="block text-gray-700 font-semibold text-xs mb-1">
                                Email<span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                placeholder="user@example.com">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-gray-700 font-semibold text-xs mb-1">
                                Password <span class="text-gray-400 font-normal">(Leave empty to keep current)</span>
                            </label>
                            <input type="password" name="password"
                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                placeholder="Enter password">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Role -->
                        <div>
                            <label class="block text-gray-700 font-semibold text-xs mb-1">
                                Role<span class="text-red-500">*</span>
                            </label>
                            <select name="role_id" id="role_id"
                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600 select2-basic">
                                <option value="">Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Row 2: Company Multi-Select && Status -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="block text-gray-700 font-semibold text-xs mb-1">
                                Access Companies<span class="text-red-500">*</span>
                            </label>
                            <select name="companies[]" id="companies" multiple
                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600 select2-multi"
                                style="max-height: 100px;">
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}"
                                        {{ in_array($company->id, $user->companies->pluck('id')->toArray()) ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('companies')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Status -->
                        <div>
                            <label class="block text-gray-700 font-semibold text-xs mb-1">
                                Status<span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status"
                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600 select2-basic">
                                <option value="1" {{ old('status', $user->status) == 1 ? 'selected' : '' }}>Active
                                </option>
                                <option value="0" {{ old('status', $user->status) == 0 ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <!-- Buttons -->
                    <div class="flex items-center justify-end space-x-2">
                        <x-secondary-button type="reset">
                            <i data-lucide="refresh-cw" class="h-4 w-4"></i> Reset
                        </x-secondary-button>
                        <x-primary-button type="submit" id="submitBtn" onclick="setLoading(this)" class="whitespace-nowrap">
                            <span class="submit-text flex items-center gap-1">
                                <i data-lucide="save" class="h-4 w-4"></i> Submit
                            </span>
                            <span class="submit-loader hidden flex items-center gap-1">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg> Submitting...
                            </span>
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Select2 CSS and JS (Latest with Tailwind Support) -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.select2-basic').select2({
                allowClear: false,
                width: '100%',
                containerCssClass: 'text-xs',
                selectionCssClass: 'text-xs'
            });
            $('.select2-multi').select2({
                allowClear: true,
                width: '100%',
                containerCssClass: 'text-xs',
                selectionCssClass: 'text-xs'
            });
        });
    </script>
@endsection