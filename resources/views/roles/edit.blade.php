@extends('layouts.app', ['breadcrumb' => 'Role', 'breadcrumbRight' => 'Dashboard -> Primary Setup -> Role'])

@section('content')
    <div class="p-4 max-w-full">
        <div class="bg-white rounded shadow-sm border border-gray-200">
            <div class="flex items-center justify-between py-2 px-3 border-b border-gray-200">
                <h6>Edit Role</h6>
                <div class="flex items-center space-x-1">
                    <a href="{{ route('roles.index') }}" class="btn-secondary">
                        <i data-lucide="list" class="h-4 w-4"></i> Role List
                    </a>
                </div>
            </div>

            <div class="p-4">
                             <!-- Form -->
                <form action="{{ route('roles.update', $role->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="permissions" value="">
                    @method('PUT')

                    <!-- Row 1: Name, Slug -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                        <!-- Name -->
                        <div>
                            <label class="block text-gray-700 font-semibold text-xs mb-1">
                                Name<span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $role->name) }}"
                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                placeholder="Role name">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Slug -->
                        <div>
                            <label class="block text-gray-700 font-semibold text-xs mb-1">
                                Slug<span class="text-red-500">*</span>
                                @if ($usersCount > 0)
                                    <span class="text-gray-400 font-normal">(Read-only)</span>
                                @endif
                            </label>
                            <input type="text" name="slug" value="{{ old('slug', $role->slug) }}"
                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                placeholder="Slug"
                                {{ $usersCount > 0 ? 'readonly' : '' }}>
                            @error('slug')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Permissions Tree -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold text-xs mb-2">Permissions</label>
                        <div class="border border-gray-300 rounded bg-white p-3 space-y-2">
                            @forelse ($permissionsHierarchy as $catIndex => $category)
                                @php
                                    // Check if any action in any item of this category is checked
                                    $categoryHasPermissions = false;
                                    foreach ($category['subcategories'] as $subcategory) {
                                        foreach ($subcategory['items'] as $item) {
                                            foreach ($item['permissions'] as $perm) {
                                                if (isset($rolePermissions[$perm->id])) {
                                                    $categoryHasPermissions = true;
                                                    break 3;
                                                }
                                            }
                                        }
                                    }
                                @endphp
                                <!-- Category Level -->
                                <div class="mb-3">
                                    <div class="flex items-center gap-2 py-2 px-2 rounded hover:bg-gray-50">
                                        <input type="checkbox"
                                            id="cat_{{ $catIndex }}"
                                            {{ $categoryHasPermissions ? 'checked' : '' }}
                                            class="category-toggle w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-1 focus:ring-blue-600 cursor-pointer">
                                        <label for="cat_{{ $catIndex }}" class="font-bold text-sm text-gray-800 cursor-pointer">{{ $category['category'] }}</label>
                                    </div>

                                    <!-- Subcategories container -->
                                    <div id="cat_container_{{ $catIndex }}" class="ml-6 {{ $categoryHasPermissions ? '' : 'hidden' }}">
                                        @foreach ($category['subcategories'] as $subIndex => $subcategory)
                                            @php
                                                // Check if any action in any item of this subcategory is checked
                                                $subcategoryHasPermissions = false;
                                                foreach ($subcategory['items'] as $item) {
                                                    foreach ($item['permissions'] as $perm) {
                                                        if (isset($rolePermissions[$perm->id])) {
                                                            $subcategoryHasPermissions = true;
                                                            break 2;
                                                        }
                                                    }
                                                }
                                            @endphp
                                            <div class="mb-2">
                                                @if (!empty($subcategory['name']))
                                                    <!-- Subcategory Level -->
                                                    <div class="flex items-center gap-2 py-1.5 px-2 rounded hover:bg-gray-50">
                                                        <input type="checkbox"
                                                            id="sub_{{ $catIndex }}_{{ $subIndex }}"
                                                            {{ $subcategoryHasPermissions ? 'checked' : '' }}
                                                            class="subcategory-toggle w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-1 focus:ring-blue-600 cursor-pointer"
                                                            data-catindex="{{ $catIndex }}">
                                                        <label for="sub_{{ $catIndex }}_{{ $subIndex }}" class="font-semibold text-xs text-gray-700 cursor-pointer">{{ $subcategory['name'] }}</label>
                                                    </div>
                                                @endif

                                                <!-- Items Level -->
                                                <div id="items_container_{{ $catIndex }}_{{ $subIndex }}" class="{{ (!empty($subcategory['name']) && !$subcategoryHasPermissions) ? 'ml-6 space-y-2 hidden mt-1' : (!empty($subcategory['name']) ? 'ml-6 space-y-2 mt-1' : 'space-y-2 mt-1') }}">
                                                    @foreach ($subcategory['items'] as $item)
                                                        @php
                                                            $permId = "module_" . $item['module'];
                                                            // Check if any action for this module is checked
                                                            $moduleHasPermissions = $item['permissions']->some(function($perm) use ($rolePermissions) {
                                                                return isset($rolePermissions[$perm->id]);
                                                            });
                                                        @endphp
                                                        <!-- Item Module Checkbox -->
                                                        <div class="flex items-start gap-2 py-1 px-2 hover:bg-gray-50 rounded">
                                                            <input type="checkbox"
                                                                id="{{ $permId }}"
                                                                class="module-toggle w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-1 focus:ring-blue-600 mt-0.5 cursor-pointer flex-shrink-0"
                                                                data-module="{{ $item['module'] }}"
                                                                data-hassub="{{ !empty($subcategory['name']) ? 'true' : 'false' }}"
                                                                {{ $moduleHasPermissions ? 'checked' : '' }}>
                                                            <div class="flex-1 ml-1">
                                                                <label for="{{ $permId }}" class="cursor-pointer block">
                                                                    <span class="font-semibold text-xs text-gray-700">{{ $item['label'] }}</span>
                                                                </label>
                                                                
                                                                <!-- Actions (Initially Hidden or Visible based on module checkbox) -->
                                                                <div id="actions_{{ $item['module'] }}" class="module-actions {{ !$moduleHasPermissions ? 'hidden' : '' }} mt-2">
                                                                    <div class="flex flex-wrap gap-4">
                                                                        @foreach ($item['permissions'] as $permission)
                                                                            @foreach ($item['availableActions'] as $action)
                                                                                @php
                                                                                    $isChecked = isset($rolePermissions[$permission->id]) &&
                                                                                                 $rolePermissions[$permission->id]->pivot->$action;
                                                                                @endphp
                                                                                <label class="flex items-center gap-1.5 cursor-pointer py-0.5 px-2 hover:bg-gray-100 rounded transition border border-transparent hover:border-gray-200">
                                                                                    <input type="checkbox"
                                                                                        name="permissions[{{ $permission->id }}][{{ $action }}]"
                                                                                        value="1"
                                                                                        {{ $isChecked ? 'checked' : '' }}
                                                                                        class="w-3.5 h-3.5 text-blue-600 border-gray-300 rounded focus:ring-1 focus:ring-blue-600">
                                                                                    <span class="text-xs text-gray-600 capitalize">{{ $action }}</span>
                                                                                </label>
                                                                            @endforeach
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-xs">No permissions available</p>
                            @endforelse
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

        // Handle category toggle
        document.querySelectorAll('.category-toggle').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const catIndex = this.id.split('_')[1];
                const catContainer = document.getElementById('cat_container_' + catIndex);
                if (this.checked) {
                    catContainer.classList.remove('hidden');
                } else {
                    catContainer.classList.add('hidden');
                    // Uncheck all subcategories inside
                    catContainer.querySelectorAll('.subcategory-toggle').forEach(sub => {
                        sub.checked = false;
                        sub.dispatchEvent(new Event('change'));
                    });
                    // Uncheck directly attached items
                    catContainer.querySelectorAll('.module-toggle[data-hassub="false"]').forEach(mod => {
                        mod.checked = false;
                        mod.dispatchEvent(new Event('change'));
                    });
                }
            });
        });

        // Handle subcategory toggle
        document.querySelectorAll('.subcategory-toggle').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const parts = this.id.split('_');
                const catIndex = parts[1];
                const subIndex = parts[2];
                const itemsContainer = document.getElementById('items_container_' + catIndex + '_' + subIndex);
                if (this.checked) {
                    itemsContainer.classList.remove('hidden');
                } else {
                    itemsContainer.classList.add('hidden');
                    // Uncheck all modules in this subcategory
                    itemsContainer.querySelectorAll('.module-toggle').forEach(mc => {
                        mc.checked = false;
                        mc.dispatchEvent(new Event('change'));
                    });
                }
            });
        });

        // Handle module toggle to show/hide actions
        document.querySelectorAll('.module-toggle').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const module = this.dataset.module;
                const actionsDiv = document.getElementById('actions_' + module);

                if (actionsDiv) {
                    if (this.checked) {
                        actionsDiv.classList.remove('hidden');
                    } else {
                        actionsDiv.classList.add('hidden');
                        // Uncheck all actions when module is unchecked
                        actionsDiv.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                            cb.checked = false;
                        });
                    }
                }
            });
        });
    </script>

@endsection
