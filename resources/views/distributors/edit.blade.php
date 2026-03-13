@extends('layouts.app', ['breadcrumb' => 'Distributor', 'breadcrumbRight' => 'Dashboard -> Primary Setup -> Distributor'])

@section('content')
    <div class="p-4 max-w-full">
        <div class="bg-white rounded shadow-sm border border-gray-200">
            <div class="flex items-center justify-between py-2 px-3 border-b border-gray-200">
                <h6>Edit Distributor</h6>
                <div class="flex items-center space-x-1">
                    <a href="{{ route('distributors.index') }}" class="btn-secondary">
                        <i data-lucide="list" class="h-4 w-4"></i> Distributor List
                    </a>
                </div>
            </div>

            <div class="p-4">
                <form action="{{ route('distributors.update', $distributor->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Row 1: Distributor Code, Name, Type, Status -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-3">
                        <!-- Distributor Code -->
                        <div>
                            <label class="block text-gray-700 font-semibold text-xs mb-1">
                                Distributor Code<span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="pid" value="{{ old('pid', $distributor->pid) }}"
                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                placeholder="e.g. DIST001">
                            @error('pid')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Name -->
                        <div>
                            <label class="block text-gray-700 font-semibold text-xs mb-1">
                                Name<span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $distributor->name) }}"
                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                placeholder="Distributor name">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Distributor Type -->
                        <div>
                            <label class="block text-gray-700 font-semibold text-xs mb-1">Distributor Type</label>
                            <select name="distributor_type" id="distributor_type"
                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600 select2-basic">
                                <option value="">-- Select Type --</option>
                                @if($distributor->distributor_type)
                                    <option value="{{ $distributor->distributor_type }}" selected>{{ $distributor->distributor_type }}</option>
                                @endif
                            </select>
                            @error('distributor_type')
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
                                <option value="Active" {{ old('status', $distributor->status) === 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ old('status', $distributor->status) === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Row 2: Principal Company Code -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-3">
                        <div>
                            <label class="block text-gray-700 font-semibold text-xs mb-1">Principal Company Code</label>
                            <select name="company_pid" id="company_pid"
                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600 select2-basic"
                                onchange="fetchCompanyDetails(this.value)">
                                <option value="">-- Select Company --</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->pid }}"
                                        {{ old('company_pid', $distributor->company_pid) === $company->pid ? 'selected' : '' }}>
                                        {{ $company->pid }} - {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('company_pid')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Dynamic Parameters -->
                    <div id="parameters-section" class="mb-3 hidden">
                        <p class="text-xs font-semibold text-gray-600 mb-2 bg-gradient-to-r from-gray-200 to-gray-100 px-2 py-1">
                            Additional Parameters
                        </p>
                        <div id="parameters-container" class="grid grid-cols-1 md:grid-cols-5 gap-3">
                            @for ($i = 1; $i <= 10; $i++)
                                <div id="param-group-{{ $i }}" class="hidden">
                                    <label id="param-label-{{ $i }}" class="block text-gray-700 font-semibold text-xs mb-1"></label>
                                    <input type="text" name="d_parameter_{{ $i }}" value="{{ old('d_parameter_' . $i, $distributor->d_parameters[$i] ?? '') }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600">
                                </div>
                            @endfor
                        </div>
                    </div>

                    <!-- Dynamic URLs -->
                    <div id="urls-section" class="mb-3 hidden">
                        <p class="text-xs font-semibold text-gray-600 mb-2 bg-gradient-to-r from-gray-200 to-gray-100 px-2 py-1">
                            Distributor URLs & Credentials
                        </p>
                        <div id="urls-container" class="space-y-4">
                            <!-- Populated via AJAX -->
                        </div>
                    </div>

                    <!-- Divider: Address -->
                    <div class="border-t border-gray-200 pt-3 mb-3">
                        <p class="text-xs font-semibold text-gray-600 mb-2">Distributor Address</p>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-3">
                            <!-- City -->
                            <div>
                                <label class="block text-gray-700 font-semibold text-xs mb-1">City</label>
                                <input type="text" name="city" value="{{ old('city', $distributor->city) }}"
                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                    placeholder="City">
                                @error('city')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- State -->
                            <div>
                                <label class="block text-gray-700 font-semibold text-xs mb-1">State</label>
                                <input type="text" name="state" value="{{ old('state', $distributor->state) }}"
                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                    placeholder="State">
                                @error('state')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Pin Code -->
                            <div>
                                <label class="block text-gray-700 font-semibold text-xs mb-1">Pin Code</label>
                                <input type="text" name="pin_code" value="{{ old('pin_code', $distributor->pin_code) }}"
                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                    placeholder="Pin Code">
                                @error('pin_code')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="block text-gray-700 font-semibold text-xs mb-1">Address</label>
                            <textarea name="address" rows="2"
                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                placeholder="Street / Colony / Area">{{ old('address', $distributor->address) }}</textarea>
                            @error('address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <!-- GST No -->
                            <div>
                                <label class="block text-gray-700 font-semibold text-xs mb-1">GST No</label>
                                <input type="text" name="gst_no" value="{{ old('gst_no', $distributor->gst_no) }}"
                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                    placeholder="GST Number">
                                @error('gst_no')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- PAN No -->
                            <div>
                                <label class="block text-gray-700 font-semibold text-xs mb-1">PAN No</label>
                                <input type="text" name="pan_no" value="{{ old('pan_no', $distributor->pan_no) }}"
                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                    placeholder="PAN Number">
                                @error('pan_no')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Divider: Contact Details -->
                    <div class="border-t border-gray-200 pt-3 mb-3">
                        <p class="text-xs font-semibold text-gray-600 mb-2">Distributor Contact Details</p>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                            <!-- Contact Name -->
                            <div>
                                <label class="block text-gray-700 font-semibold text-xs mb-1">Name</label>
                                <input type="text" name="contact_name" value="{{ old('contact_name', $distributor->contact_name) }}"
                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                    placeholder="Contact name">
                                @error('contact_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Designation -->
                            <div>
                                <label class="block text-gray-700 font-semibold text-xs mb-1">Designation</label>
                                <input type="text" name="designation" value="{{ old('designation', $distributor->designation) }}"
                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                    placeholder="Designation">
                                @error('designation')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Email -->
                            <div>
                                <label class="block text-gray-700 font-semibold text-xs mb-1">Email</label>
                                <input type="email" name="email" value="{{ old('email', $distributor->email) }}"
                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                    placeholder="email@example.com">
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Mobile -->
                            <div>
                                <label class="block text-gray-700 font-semibold text-xs mb-1">Mobile</label>
                                <input type="text" name="mobile" value="{{ old('mobile', $distributor->mobile) }}"
                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                    placeholder="Mobile number">
                                @error('mobile')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Distributor Location -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold text-xs mb-1">Distributor Location</label>
                        <input type="text" name="distributor_location" value="{{ old('distributor_location', $distributor->distributor_location) }}"
                            class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                            placeholder="e.g. Zone / Region / GPS coordinates">
                        @error('distributor_location')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
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
        // Global for saved data
        const savedUrls = @json($distributor->c_urls ?? []);

        document.addEventListener('DOMContentLoaded', function() {
            $('.select2-basic').select2({
                allowClear: false,
                width: '100%',
                containerCssClass: 'text-xs',
                selectionCssClass: 'text-xs'
            });

            // If company is already selected, fetch details
            const initialCompany = $('#company_pid').val();
            if (initialCompany) {
                fetchCompanyDetails(initialCompany, "{{ $distributor->distributor_type }}");
            }
        });

        function fetchCompanyDetails(companyPid, selectedType = null) {
            if (!companyPid) {
                $('#distributor_type').html('<option value="">-- Select Type --</option>');
                $('#parameters-section').addClass('hidden');
                $('#urls-section').addClass('hidden');
                return;
            }

            $.ajax({
                url: `/distributors/get-company-details/${companyPid}`,
                method: 'GET',
                success: function(data) {
                    // Populate Distributor Types
                    let typeHtml = '<option value="">-- Select Type --</option>';
                    if (data.distributor_types && data.distributor_types.length > 0) {
                        data.distributor_types.forEach(type => {
                            const selected = (selectedType === type) ? 'selected' : '';
                            typeHtml += `<option value="${type}" ${selected}>${type}</option>`;
                        });
                    }
                    $('#distributor_type').html(typeHtml);

                    // Populate Parameters
                    let hasParams = false;
                    for (let i = 1; i <= 10; i++) {
                        const label = data.parameters[i];
                        if (label) {
                            $(`#param-label-${i}`).text(label);
                            $(`#param-group-${i}`).removeClass('hidden');
                            hasParams = true;
                        } else {
                            $(`#param-group-${i}`).addClass('hidden');
                        }
                    }

                    if (hasParams) {
                        $('#parameters-section').removeClass('hidden');
                    } else {
                        $('#parameters-section').addClass('hidden');
                    }

                    // Populate URLs
                    const urlsContainer = document.getElementById('urls-container');
                    urlsContainer.innerHTML = '';
                    if (data.c_urls && data.c_urls.length > 0) {
                        data.c_urls.forEach((urlConfig, i) => {
                            // Find saved data for this URL if it matches
                            const savedUrl = savedUrls.find(s => s.url === urlConfig.url) || {};
                            
                            const urlDiv = document.createElement('div');
                            urlDiv.className = 'p-3 border border-gray-200 rounded bg-gray-50';
                            urlDiv.innerHTML = `
                                <div class="mb-2">
                                    <label class="block text-gray-700 font-bold text-xs mb-1">URL ${i + 1}: <span class="text-blue-600">${urlConfig.url}</span></label>
                                    <input type="hidden" name="c_urls[${i}][url]" value="${urlConfig.url}">
                                </div>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    ${(urlConfig.fields || []).map((field, j) => {
                                        const savedField = (savedUrl.fields || []).find(f => f.key === field.key) || {};
                                        const value = savedField.value || '';
                                        return `
                                            <div>
                                                <label class="block text-gray-700 font-semibold text-[10px] mb-1">${field.key}</label>
                                                <input type="hidden" name="c_urls[${i}][fields][${j}][key]" value="${field.key}">
                                                <input type="text" name="c_urls[${i}][fields][${j}][value]" value="${value}" 
                                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                                    placeholder="Enter ${field.key}">
                                            </div>
                                        `;
                                    }).join('')}
                                </div>
                            `;
                            urlsContainer.appendChild(urlDiv);
                        });
                        $('#urls-section').removeClass('hidden');
                    } else {
                        $('#urls-section').addClass('hidden');
                    }
                    lucide.createIcons();
                },
                error: function() {
                    console.error('Failed to fetch company details');
                }
            });
        }
    </script>
@endsection
