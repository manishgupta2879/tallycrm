@extends('layouts.app', ['breadcrumb' => 'Create Company', 'breadcrumbRight' => 'Dashboard->Primary Setup->Company->Create'])

@section('content')
    <div class="max-w-full">
        <div class="bg-white rounded shadow-sm border border-gray-200">
            <div class="flex items-center justify-between py-2 px-3 border-b border-gray-200">
                <h6>Create Company</h6>
                <div class="flex items-center space-x-1">
                    <a href="{{ route('companies.index') }}" class="btn-secondary">
                        <i data-lucide="list" class="h-4 w-4"></i> Company List
                    </a>
                </div>
            </div>

            <form action="{{ route('companies.store') }}" method="POST">
                @csrf
                <div class="overflow-x-auto px-4 relative" style="max-height: calc(100vh - 263px);">
                    <!-- Row 1: Company Code, Name, Status (Sticky Header) -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3 pt-2 sticky top-0 bg-white z-10 transition-shadow"
                        id="stickyHeader">
                        <!-- Company Code (PID) -->
                        <div>
                            <label class="block text-gray-700 font-semibold text-xs mb-1">
                                Company Code/PID<span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="pid" value="{{ old('pid') }}"
                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                placeholder="e.g. SONY001">
                            @error('pid')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Name -->
                        <div>
                            <label class="block text-gray-700 font-semibold text-xs mb-1">
                                Company Name<span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                placeholder="Company name">
                            @error('name')
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
                                <option value="Active" {{ old('status', 'Active') === 'Active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="Inactive" {{ old('status') === 'Inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Divider: Company Contact Person -->
                    <div class="mb-3">
                        <p
                            class="text-xs font-semibold text-gray-600 mb-2 bg-gradient-to-r from-gray-200 to-gray-100 px-2 py-1">
                            Company Contacts Details</p>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-2">
                            <!-- Contact Name -->
                            <div>
                                <label class="block text-gray-700 font-semibold text-xs mb-1">Contact Name</label>
                                <input type="text" name="contact_name" value="{{ old('contact_name') }}"
                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                    placeholder="Contact name">
                                @error('contact_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Designation -->
                            <div>
                                <label class="block text-gray-700 font-semibold text-xs mb-1">Designation</label>
                                <input type="text" name="designation" value="{{ old('designation') }}"
                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                    placeholder="Designation">
                                @error('designation')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Email -->
                            <div>
                                <label class="block text-gray-700 font-semibold text-xs mb-1">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                    placeholder="email@example.com">
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Mobile -->
                            <div>
                                <label class="block text-gray-700 font-semibold text-xs mb-1">Mobile</label>
                                <input type="text" name="mobile" value="{{ old('mobile') }}"
                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                    placeholder="Mobile number">
                                @error('mobile')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <!-- Territory / Area of Operations -->
                        <div>
                            <label class="block text-gray-700 font-semibold text-xs mb-1">Territory / Area of
                                Operations</label>
                            <input type=" text" name="territory" value="{{ old('territory') }}"
                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                placeholder="e.g. North India, Maharashtra, Pan India">
                            @error('territory')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-2">
                            <p
                                class="text-xs font-semibold text-gray-600 mb-2 bg-gradient-to-r from-gray-200 to-gray-100 px-2 py-1">
                                Distributor Types
                            </p>
                            <div id="types-container" class="space-y-2 mb-2">
                                <div class="flex items-center space-x-2">
                                    <input type="text" name="d_types[]"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                        placeholder="Type name (e.g. Retailer)">
                                    <button type="button" class="btn-primary py-1 px-2" onclick="addType()">
                                        <i data-lucide="plus" class="h-3 w-3"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <p
                                class="text-xs font-semibold text-gray-600 mb-2 bg-gradient-to-r from-gray-200 to-gray-100 px-2 py-1">
                                Distributor Parameters (Max 10)
                            </p>
                            <div id="parameters-container" class="space-y-2 mb-2">
                                <div class="flex items-center space-x-2">
                                    <input type="text" name="d_parameter[]"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                        placeholder="Parameter Label (e.g. VAT Number)">
                                    <button type="button" id="add-parameter-btn" class="btn-primary py-1 px-2"
                                        onclick="addParameter()">
                                        <i data-lucide="plus" class="h-3 w-3"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <p
                            class="text-xs font-semibold text-gray-600 mb-2 bg-gradient-to-r from-gray-200 to-gray-100 px-2 py-1">
                            No of urls
                        </p>
                        <div class="mb-2">
                            <select id="no_of_urls" name="no_of_urls"
                                class="w-1/4 px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                onchange="generateUrlInputs()">
                                <option value="0">-- Select Count --</option>
                                @for ($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ old('no_of_urls') == $i ? 'selected' : '' }}>
                                        {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div id="urls-configuration"
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-4">
                            @if (is_array(old('c_urls')))
                                @foreach (old('c_urls') as $uIndex => $urlData)
                                    <div class="p-3 border border-gray-200 rounded bg-gray-50 url-block relative">
                                        <button type="button"
                                            class="absolute top-[-11px] right-[-11px] bg-gray-500 text-white hover:bg-red-600 rounded-full shadow-sm p-1"
                                            onclick="removeUrlBlock(this)" title="Remove URL">
                                            <i data-lucide="x" class="h-4 w-4"></i>
                                        </button>
                                        <div class="mb-2">
                                            <label class="block text-gray-700 font-semibold text-xs mb-1 url-label">URL
                                                {{ $uIndex + 1 }}</label>
                                            <div class="flex items-center justify-between gap-2 mb-1">
                                                <input type="text" name="c_urls[{{ $uIndex }}][url]"
                                                    value="{{ $urlData['url'] ?? '' }}"
                                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                                    placeholder="https://example.com">
                                                <button type="button" class="btn-primary py-0.5 px-2 text-[10px] flex-shrink-0"
                                                    onclick="addField({{ $uIndex }})">
                                                    <i data-lucide="plus" class="h-3 w-3"></i> Add Field
                                                </button>
                                            </div>
                                        </div>
                                        <div id="fields-container-{{ $uIndex }}" class="space-y-2">
                                            @if (isset($urlData['fields']) && is_array($urlData['fields']))
                                                @foreach ($urlData['fields'] as $fIndex => $field)
                                                    <div class="flex items-center space-x-2 field-row">
                                                        <input type="text"
                                                            name="c_urls[{{ $uIndex }}][fields][{{ $fIndex }}][key]"
                                                            value="{{ $field['key'] ?? '' }}"
                                                            class="flex-1 min-w-0 px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                                            placeholder="Key">
                                                        <input type="text"
                                                            name="c_urls[{{ $uIndex }}][fields][{{ $fIndex }}][value]"
                                                            value="{{ $field['value'] ?? '' }}"
                                                            class="flex-1 min-w-0 px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                                            placeholder="Value">
                                                        <button type="button"
                                                            class="bg-red-500 text-white hover:bg-red-600 py-1 px-2 rounded flex-shrink-0 flex items-center justify-center shadow-sm"
                                                            onclick="this.parentElement.remove()" title="Remove Field">
                                                            <i data-lucide="trash-2" class="h-3 w-3"></i>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                </div>

                <!-- Buttons (Sticky Footer) -->
                <div class="flex items-center justify-end space-x-2 px-4 py-2 border-t border-gray-200 ">
                    <x-primary-button type="submit" id="submitBtn" onclick="setLoading(this)"
                        class="whitespace-nowrap">
                        <span class="submit-text flex items-center gap-1">
                            <i data-lucide="save" class="h-4 w-4"></i> Submit
                        </span>
                        <span class="submit-loader hidden flex items-center gap-1">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg> Submitting...
                        </span>
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

    <!-- Select2 -->
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
        });

        function addType() {
            const container = document.getElementById('types-container');
            const div = document.createElement('div');
            div.className = 'flex items-center space-x-2';
            div.innerHTML = `
                    <input type="text" name="d_types[]" class="flex-1 min-w-0 px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600" placeholder="Type name (e.g. Wholesaler)">
                    <button type="button" class="bg-red-500 text-white hover:bg-red-600 py-1 px-3 rounded text-xs flex items-center justify-center gap-1 shadow-sm flex-shrink-0" onclick="removeElement(this, 'types')">
                        <i data-lucide="trash-2" class="h-3 w-3"></i>
                    </button>
                `;
            container.appendChild(div);
            lucide.createIcons();
        }

        function addParameter() {
            const container = document.getElementById('parameters-container');
            const currentParams = container.querySelectorAll('input[name="d_parameter[]"]').length;

            if (currentParams >= 10) {
                alert('Maximum 10 parameters allowed.');
                return;
            }

            const div = document.createElement('div');
            div.className = 'flex items-center space-x-2';
            div.innerHTML =
                `
                                                                                                                                <input type="text" name="d_parameter[]" class="flex-1 min-w-0 px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600" placeholder="Parameter Label">
                                                                                                                                <button type="button" class="bg-red-500 text-white hover:bg-red-600 py-1 px-3 rounded text-xs flex items-center justify-center gap-1 shadow-sm flex-shrink-0" onclick="removeElement(this, 'params')">
                                                                                                                                    <i data-lucide="trash-2" class="h-3 w-3"></i>
                                                                                                                                </button>
                                                                                                                            `;
            container.appendChild(div);
            lucide.createIcons();

            if (container.querySelectorAll('input[name="d_parameter[]"]').length >= 10) {
                document.getElementById('add-parameter-btn').classList.add('hidden');
            }
        }

        function removeElement(button, type) {
            button.parentElement.remove();
            if (type === 'params') {
                const container = document.getElementById('parameters-container');
                if (container.querySelectorAll('input[name="d_parameter[]"]').length < 10) {
                    document.getElementById('add-parameter-btn').classList.remove('hidden');
                }
            }
        }

        @php
            $maxUrlIndex = -1;
            if (is_array(old('c_urls'))) {
                foreach (array_keys(old('c_urls')) as $key) {
                    if (is_numeric($key) && $key > $maxUrlIndex) {
                        $maxUrlIndex = $key;
                    }
                }
            }
            $initialUrlIndex = $maxUrlIndex + 1;
        @endphp
        let urlIndexCounter = {{ $initialUrlIndex }};

        function generateUrlInputs() {
            const count = parseInt(document.getElementById('no_of_urls').value);
            const container = document.getElementById('urls-configuration');
            const currentBlocks = container.querySelectorAll('.url-block');
            const currentCount = currentBlocks.length;

            if (count < currentCount) {
                if (!confirm(
                        `Are you sure you want to remove ${currentCount - count} URL configuration(s)? This will delete any entered data for those URLs.`
                    )) {
                    document.getElementById('no_of_urls').value = currentCount;
                    return;
                }
                // Remove from the end
                for (let i = currentCount - 1; i >= count; i--) {
                    currentBlocks[i].remove();
                }
                updateUrlLabels();
            } else if (count > currentCount) {
                // Add new ones
                for (let i = currentCount; i < count; i++) {
                    const idx = urlIndexCounter++;
                    const urlDiv = document.createElement('div');
                    urlDiv.className = 'p-3 border border-gray-200 rounded bg-gray-50 url-block relative';
                    urlDiv.innerHTML = `
                        <button type="button" class="absolute top-[-11px] right-[-11px] bg-gray-500 text-white hover:bg-red-600 rounded-full shadow-sm p-1" onclick="removeUrlBlock(this)" title="Remove URL">
                            <i data-lucide="x" class="h-4 w-4"></i>
                        </button>
                        <div class="mb-2">
                            <label class="block text-gray-700 font-semibold text-xs mb-1 url-label">URL ${idx + 1}</label>
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <input type="text" name="c_urls[${idx}][url]" class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600" placeholder="https://example.com">
                                <button type="button" class="btn-primary py-0.5 px-2 text-[10px] flex-shrink-0" onclick="addField(${idx})">
                                    <i data-lucide="plus" class="h-3 w-3"></i> Add Field
                                </button>
                            </div>
                        </div>
                        <div id="fields-container-${idx}" class="space-y-2">
                        </div>
                    `;
                    container.appendChild(urlDiv);
                }
                updateUrlLabels();
                lucide.createIcons();
            }
        }

        function removeUrlBlock(button) {
            if (confirm('Are you sure you want to remove this URL configuration?')) {
                button.closest('.url-block').remove();
                updateUrlLabels();
                const currentCount = document.querySelectorAll('.url-block').length;
                document.getElementById('no_of_urls').value = currentCount;
            }
        }

        function updateUrlLabels() {
            const blocks = document.querySelectorAll('.url-block');
            blocks.forEach((block, index) => {
                const label = block.querySelector('.url-label');
                if (label) {
                    label.textContent = 'URL ' + (index + 1);
                }
            });
        }

        function addField(urlIndex) {
            const container = document.getElementById(`fields-container-${urlIndex}`);
            const fieldRows = container.querySelectorAll('.field-row');
            
            let maxFieldIndex = -1;
            fieldRows.forEach(row => {
                const inputs = row.querySelectorAll('input');
                inputs.forEach(input => {
                    const nameMatch = input.name.match(/\[fields\]\[(\d+)\]/);
                    if (nameMatch) {
                        const idx = parseInt(nameMatch[1]);
                        if (idx > maxFieldIndex) maxFieldIndex = idx;
                    }
                });
            });
            
            const nextFieldIndex = maxFieldIndex + 1;

            if (fieldRows.length >= 12) {
                alert('Maximum 12 fields allowed per URL.');
                return;
            }

            const fieldRow = document.createElement('div');
            fieldRow.className = 'flex items-center space-x-2 field-row';
            fieldRow.innerHTML =
                `
                <input type="text" name="c_urls[${urlIndex}][fields][${nextFieldIndex}][key]" class="flex-1 min-w-0 px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600" placeholder="Key">
                <input type="text" name="c_urls[${urlIndex}][fields][${nextFieldIndex}][value]" class="flex-1 min-w-0 px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600" placeholder="Value">
                <button type="button" class="bg-red-500 text-white hover:bg-red-600 py-1 px-2 rounded flex-shrink-0 flex items-center justify-center shadow-sm" onclick="this.parentElement.remove()" title="Remove Field">
                    <i data-lucide="trash-2" class="h-3 w-3"></i>
                </button>
            `;
            container.appendChild(fieldRow);
            lucide.createIcons();
        }
    </script>
@endsection
