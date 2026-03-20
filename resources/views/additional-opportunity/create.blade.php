@extends('layouts.app', ['breadcrumb' => 'Additional Opportunities', 'breadcrumbRight' => 'Dashboard->Additional Opportunity->Additional Oopportunities'])

@section('content')
    <div class="p-4 max-w-full">
        <div class="bg-white rounded shadow-sm border border-gray-200">
            <div class="flex items-center justify-between py-2 px-3 border-b border-gray-200">
                <h6>Create Additional Opportunity</h6>
                <div class="flex items-center space-x-1">
                    <a href="{{ route('additional-opportunities.index') }}" class="btn-secondary">
                        <i data-lucide="list" class="h-4 w-4"></i> Additional Opportunity List
                    </a>
                </div>
            </div>

            <div class="p-4">
                <!-- Form -->
                <form action="{{ route('additional-opportunities.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                        <!-- Name -->
                        <div>
                            <label class="block text-gray-700 font-semibold text-xs mb-1">
                                Company Name<span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="company_name" value="{{ old('company_name') }}"
                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                placeholder="Company name">
                            @error('company_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold text-xs mb-1">Category</label>
                            <select name="category_id" id="category_id"
                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600 select2-basic">
                                <option value="">-- Select Type --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-3 col-span-1 md:col-span-2">
                            <label class="block text-gray-700 font-semibold text-xs mb-1">Description</label>
                            <textarea name="description" rows="2"
                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                placeholder="Description">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-end space-x-2">
                        <x-secondary-button type="reset">
                            <i data-lucide="refresh-cw" class="h-4 w-4"></i> Reset
                        </x-secondary-button>
                        <x-primary-button type="submit" id="submitBtn" onclick="setLoading(this)"
                            class="whitespace-nowrap">
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

    <script>
        function setLoading(btn) {
            const submitText = btn.querySelector('.submit-text');
            const submitLoader = btn.querySelector('.submit-loader');
            if (submitText && submitLoader) {
                submitText.classList.add('hidden');
                submitLoader.classList.remove('hidden');
                btn.disabled = true;
            }
        }
    </script>
@endsection
