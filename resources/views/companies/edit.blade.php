@extends('layouts.app', ['breadcrumb' => 'Company', 'breadcrumbRight' => 'Dashboard -> Primary Setup -> Company'])

@section('content')
    <div class="p-4 max-w-full">
        <div class="bg-white rounded shadow-sm border border-gray-200">
            <div class="flex items-center justify-between py-2 px-3 border-b border-gray-200">
                <h6>Edit Company</h6>
                <div class="flex items-center space-x-1">
                    <a href="{{ route('companies.index') }}" class="btn-secondary">
                        <i data-lucide="list" class="h-4 w-4"></i> Company List
                    </a>
                </div>
            </div>

            <div class="p-4">
                <!-- Form -->
                <form action="{{ route('companies.update', $company->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Row 1: Company Code, Name, Status -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
                        <!-- Company Code (PID) -->
                        <div>
                            <label class="block text-gray-700 font-semibold text-xs mb-1">
                                Company Code<span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="pid" value="{{ old('pid', $company->pid) }}"
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
                            <input type="text" name="name" value="{{ old('name', $company->name) }}"
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
                                <option value="Active" {{ old('status', $company->status) === 'Active' ? 'selected' : '' }}>
                                    Active</option>
                                <option value="Inactive" {{ old('status', $company->status) === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Divider: Contact Person -->
                    <div class="border-t border-gray-200 pt-3 mb-3">
                        <p class="text-xs font-semibold text-gray-600 mb-2">Contact Person Details</p>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                            <!-- Contact Name -->
                            <div>
                                <label class="block text-gray-700 font-semibold text-xs mb-1">Contact Name</label>
                                <input type="text" name="contact_name"
                                    value="{{ old('contact_name', $company->contact_name) }}"
                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                    placeholder="Contact name">
                                @error('contact_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Designation -->
                            <div>
                                <label class="block text-gray-700 font-semibold text-xs mb-1">Designation</label>
                                <input type="text" name="designation"
                                    value="{{ old('designation', $company->designation) }}"
                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                    placeholder="Designation">
                                @error('designation')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Email -->
                            <div>
                                <label class="block text-gray-700 font-semibold text-xs mb-1">Email</label>
                                <input type="email" name="email" value="{{ old('email', $company->email) }}"
                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                    placeholder="email@example.com">
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Mobile -->
                            <div>
                                <label class="block text-gray-700 font-semibold text-xs mb-1">Mobile</label>
                                <input type="text" name="mobile" value="{{ old('mobile', $company->mobile) }}"
                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                    placeholder="Mobile number">
                                @error('mobile')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Territory / Area of Operations -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold text-xs mb-1">Territory / Area of Operations</label>
                        <input type="text" name="territory" value="{{ old('territory', $company->territory) }}"
                            class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                            placeholder="e.g. North India, Maharashtra, Pan India">
                        @error('territory')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <p
                            class="text-xs font-semibold text-gray-600 mb-2 bg-gradient-to-r from-gray-200 to-gray-100 px-2 py-1">
                            Distributor Types
                        </p>
                        <div id="types-container" class="space-y-2 mb-2">
                            @if(is_array($company->d_types) && count($company->d_types) > 0)
                                @foreach($company->d_types as $index => $type)
                                    <div class="flex items-center space-x-2">
                                        <input type="text" name="d_types[]" value="{{ $type }}"
                                            class="w-1/3 px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                            placeholder="Type name">
                                        @if($index == 0)
                                            <button type="button" class="btn-primary py-1 px-2" onclick="addType()">
                                                <i data-lucide="plus" class="h-3 w-3"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn-danger py-1 px-2" onclick="removeType(this)">
                                                <i data-lucide="minus" class="h-3 w-3"></i>
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="flex items-center space-x-2">
                                    <input type="text" name="d_types[]"
                                        class="w-1/3 px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                        placeholder="Type name (e.g. Retailer)">
                                    <button type="button" class="btn-primary py-1 px-2" onclick="addType()">
                                        <i data-lucide="plus" class="h-3 w-3"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <p
                            class="text-xs font-semibold text-gray-600 mb-2 bg-gradient-to-r from-gray-200 to-gray-100 px-2 py-1">
                            Distributor Parameters (Max 10)
                        </p>
                        <div id="parameters-container" class="space-y-2 mb-2">
                            @if(is_array($company->d_parameter) && count($company->d_parameter) > 0)
                                @foreach($company->d_parameter as $index => $param)
                                    <div class="flex items-center space-x-2">
                                        <input type="text" name="d_parameter[]" value="{{ $param }}"
                                            class="w-1/3 px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                            placeholder="Parameter Label">
                                        @if($index == 0)
                                            <button type="button" id="add-parameter-btn"
                                                class="btn-primary py-1 px-2 {{ count($company->d_parameter) >= 10 ? 'hidden' : '' }}"
                                                onclick="addParameter()">
                                                <i data-lucide="plus" class="h-3 w-3"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn-danger py-1 px-2" onclick="removeElement(this, 'params')">
                                                <i data-lucide="minus" class="h-3 w-3"></i>
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="flex items-center space-x-2">
                                    <input type="text" name="d_parameter[]"
                                        class="w-1/3 px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                        placeholder="Parameter Label (e.g. VAT Number)">
                                    <button type="button" id="add-parameter-btn" class="btn-primary py-1 px-2"
                                        onclick="addParameter()">
                                        <i data-lucide="plus" class="h-3 w-3"></i>
                                    </button>
                                </div>
                            @endif
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
                                @php
                                    $currentUrlCount = old('no_of_urls', (is_array($company->c_urls) ? count($company->c_urls) : 0));
                                @endphp
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ $currentUrlCount == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div id="urls-configuration" class="space-y-4">
                            @if(is_array($company->c_urls))
                                @foreach($company->c_urls as $uIndex => $urlData)
                                    <div class="p-3 border border-gray-200 rounded bg-gray-50 url-block">
                                        <div class="mb-2">
                                             <label class="block text-gray-700 font-semibold text-xs mb-1">URL
                                                 {{ $uIndex + 1 }}</label>
                                             <input type="text" name="c_urls[{{ $uIndex }}][url]" value="{{ old('c_urls.'.$uIndex.'.url', $urlData['url'] ?? '') }}"
                                                 class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                                 placeholder="https://example.com">
                                         </div>
                                         <div id="fields-container-{{ $uIndex }}" class="ml-4 space-y-2">
                                             <div class="flex items-center justify-between mb-1">
                                                 <span class="text-xs font-semibold text-gray-600">Fields (Max 12)</span>
                                                 <button type="button" class="btn-primary py-0.5 px-2 text-[10px]"
                                                     onclick="addField({{ $uIndex }})">
                                                     <i data-lucide="plus" class="h-3 w-3"></i> Add Field
                                                 </button>
                                             </div>
                                             @if(isset($urlData['fields']) && is_array($urlData['fields']))
                                                 @foreach($urlData['fields'] as $fIndex => $field)
                                                     <div class="flex items-center space-x-2 field-row">
                                                         <input type="text" name="c_urls[{{ $uIndex }}][fields][{{ $fIndex }}][key]"
                                                             value="{{ old('c_urls.'.$uIndex.'.fields.'.$fIndex.'.key', $field['key'] ?? '') }}"
                                                             class="w-1/3 px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                                             placeholder="Key">
                                                         <input type="text" name="c_urls[{{ $uIndex }}][fields][{{ $fIndex }}][value]"
                                                             value="{{ old('c_urls.'.$uIndex.'.fields.'.$fIndex.'.value', $field['value'] ?? '') }}"
                                                             class="w-1/3 px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                                             placeholder="Value">
                                                         <button type="button" class="btn-danger py-1 px-2"
                                                             onclick="this.parentElement.remove()">
                                                             <i data-lucide="minus" class="h-3 w-3"></i>
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

                    <!-- Buttons -->
                    <div class="flex items-center justify-end space-x-2">
                        <x-secondary-button type="reset">
                            <i data-lucide="refresh-cw" class="h-4 w-4"></i> Reset
                        </x-secondary-button>
                        <x-primary-button type="submit" id="submitBtn" onclick="setLoading(this)" class="whitespace-nowrap">
                            <span class="submit-text flex items-center gap-1">
                                <i data-lucide="save" class="h-4 w-4"></i> Update
                            </span>
                            <span class="submit-loader hidden flex items-center gap-1">
                                <i data-lucide="loader" class="h-4 w-4 animate-spin"></i> Updating...
                            </span>
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('.select2-basic').select2({
                allowClear: false,
                width: '100%',
                containerCssClass: 'text-xs',
                selectionCssClass: 'text-xs'
            });
            lucide.createIcons();
        });

        function addType() {
            const container = document.getElementById('types-container');
            const div = document.createElement('div');
            div.className = 'flex items-center space-x-2';
            div.innerHTML = `
                    <input type="text" name="d_types[]" class="w-1/3 px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600" placeholder="Type name (e.g. Wholesaler)">
                    <button type="button" class="btn-danger py-1 px-2" onclick="removeElement(this, 'types')">
                        <i data-lucide="minus" class="h-3 w-3"></i>
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
            div.innerHTML = `
                    <input type="text" name="d_parameter[]" class="w-1/3 px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600" placeholder="Parameter Label">
                    <button type="button" class="btn-danger py-1 px-2" onclick="removeElement(this, 'params')">
                        <i data-lucide="minus" class="h-3 w-3"></i>
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

        let prevUrlCount = {{ is_array($company->c_urls) ? count($company->c_urls) : 0 }};

        function generateUrlInputs() {
            const count = parseInt(document.getElementById('no_of_urls').value);
            const container = document.getElementById('urls-configuration');
            const currentBlocks = container.querySelectorAll('.url-block');
            const currentCount = currentBlocks.length;

            if (count < currentCount) {
                if (!confirm(`Are you sure you want to remove ${currentCount - count} URL configuration(s)? This will delete any entered data for those URLs.`)) {
                    document.getElementById('no_of_urls').value = currentCount;
                    return;
                }
                // Remove from the end
                for (let i = currentCount - 1; i >= count; i--) {
                    currentBlocks[i].remove();
                }
            } else if (count > currentCount) {
                // Add new ones
                for (let i = currentCount; i < count; i++) {
                    const urlDiv = document.createElement('div');
                    urlDiv.className = 'p-3 border border-gray-200 rounded bg-gray-50 url-block';
                    urlDiv.innerHTML = `
                        <div class="mb-2">
                            <label class="block text-gray-700 font-semibold text-xs mb-1">URL ${i + 1}</label>
                            <input type="text" name="c_urls[${i}][url]" class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600" placeholder="https://example.com">
                        </div>
                        <div id="fields-container-${i}" class="ml-4 space-y-2">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-semibold text-gray-600">Fields (Max 12)</span>
                                <button type="button" class="btn-primary py-0.5 px-2 text-[10px]" onclick="addField(${i})">
                                    <i data-lucide="plus" class="h-3 w-3"></i> Add Field
                                </button>
                            </div>
                        </div>
                    `;
                    container.appendChild(urlDiv);
                }
                lucide.createIcons();
            }
            prevUrlCount = count;
        }

        function addField(urlIndex) {
            const container = document.getElementById(`fields-container-${urlIndex}`);
            const currentFields = container.querySelectorAll('.field-row').length;

            if (currentFields >= 12) {
                alert('Maximum 12 fields allowed per URL.');
                return;
            }

            const fieldRow = document.createElement('div');
            fieldRow.className = 'flex items-center space-x-2 field-row';
            fieldRow.innerHTML = `
                    <input type="text" name="c_urls[${urlIndex}][fields][${currentFields}][key]" class="w-1/3 px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600" placeholder="Key">
                    <input type="text" name="c_urls[${urlIndex}][fields][${currentFields}][value]" class="w-1/3 px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600" placeholder="Value">
                    <button type="button" class="btn-danger py-1 px-2" onclick="this.parentElement.remove()">
                        <i data-lucide="minus" class="h-3 w-3"></i>
                    </button>
                `;
            container.appendChild(fieldRow);
            lucide.createIcons();
        }
    </script>
@endsection