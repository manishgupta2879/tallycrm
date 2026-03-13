@extends('layouts.app', ['breadcrumb' => 'Categories', 'breadcrumbRight' => 'Dashboard -> Additional Opportunity -> Categories'])

@section('content')
    <div class="p-4 max-w-full">
        <div class="bg-white rounded shadow-sm border border-gray-200">
            <div class="flex items-center justify-between py-2 px-3 border-b border-gray-200">
                <h6>Edit Category</h6>
                <div class="flex items-center space-x-1">
                    <a href="{{ route('categories.index') }}" class="btn-secondary">
                        <i data-lucide="list" class="h-4 w-4"></i> Category List
                    </a>
                </div>
            </div>

            <div class="p-4">
                             <!-- Form -->
                <form action="{{ route('categories.update', $category->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="permissions" value="">
                    @method('PUT')

                    <!-- Row 1: Name, Slug -->
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-3 mb-4">
                        <!-- Name -->
                        <div>
                            <label class="block text-gray-700 font-semibold text-xs mb-1">
                                Name<span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $category->name) }}"
                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                placeholder="Role name">
                            @error('name')
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
                                <i data-lucide="loader" class="h-4 w-4 animate-spin"></i> Submitting...
                            </span>
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </script>

@endsection
