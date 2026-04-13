@extends('layouts.app', ['breadcrumb' => 'Create Distributor', 'breadcrumbRight' => 'Dashboard->Primary Setup->Distributor->Create'])

@section('content')
    <div class="max-w-full">
        <div class="bg-white rounded shadow-sm border border-gray-200">
            <div class="flex items-center justify-between py-2 px-3 border-b border-gray-200">
                <h6>Create Distributor</h6>
                <div class="flex items-center space-x-1">
                    <a href="{{ route('distributors.index') }}" class="btn-secondary">
                        <i data-lucide="list" class="h-4 w-4"></i> Distributor List
                    </a>
                </div>
            </div>

            <div>
                <form action="{{ route('distributors.store') }}" method="POST">
                    @csrf
                    <div class="overflow-x-auto px-4 relative" style="max-height: calc(100vh - 263px);">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-3 pt-2 sticky top-0 bg-white z-10 transition-shadow"
                            id="stickyHeader">
                            <!-- Distributor Code -->
                            <div>
                                <label class="block text-gray-700 font-semibold text-xs mb-1">
                                    Distributor Code<span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="code" value="{{ old('code') }}" required
                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                    placeholder="e.g. DIST001">
                                @error('code')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Name -->
                            <div>
                                <label class="block text-gray-700 font-semibold text-xs mb-1">
                                    Name<span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                    placeholder="Distributor name">
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Company -->
                            <div>
                                <label class="block text-gray-700 font-semibold text-xs mb-1">Principal Company<span
                                        class="text-red-500">*</span></label>
                                <select name="company_code" id="company_code" onchange="fetchCompanyDetails(this.value)"
                                    required
                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600 select2-basic">
                                    <option value="">-- Select Company --</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->pid }}"
                                            {{ old('company_code') == $company->pid ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('company_code')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Distributor Type -->
                            <div>
                                <label class="block text-gray-700 font-semibold text-xs mb-1">Distributor Type<span
                                        class="text-red-500">*</span></label>
                                <select name="type" id="type" required
                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600 select2-basic">
                                    <option value="">-- Select Type --</option>
                                </select>
                                @error('type')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Status -->
                            <div>
                                <label class="block text-gray-700 font-semibold text-xs mb-1">
                                    Status<span class="text-red-500">*</span>
                                </label>
                                <select name="status" id="status" required
                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600 select2-basic">
                                    @foreach ($statusOptions as $option)
                                        <option value="{{ $option }}"
                                            {{ old('status', 'Active') === $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-3 mt-2">
                            {{-- UserId --}}
                            <div>
                                <label class="block text-gray-700 font-semibold text-xs mb-1"> User Id</label>
                                <input type="text" name="userid" value="{{ old('userid') }}"
                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                    placeholder="User Id">
                            </div>
                            {{-- Password --}}
                            <div>
                                <label class="block text-gray-700 font-semibold text-xs mb-1"> Password</label>
                                <input type="text" name="dist_perm_pass" value="{{ old('dist_perm_pass') }}"
                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                    placeholder="Password">
                            </div>
                            {{-- Auth Code --}}
                            <div>
                                <label class="block text-gray-700 font-semibold text-xs mb-1"> Auth Code</label>
                                <input type="text" name="authcode" value="{{ old('authcode') }}"
                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                    placeholder="Auth Code">
                            </div>
                            {{-- Auth Code2 --}}
                            <div>
                                <label class="block text-gray-700 font-semibold text-xs mb-1"> Auth Code2</label>
                                <input type="text" name="authcode2" value="{{ old('authcode2') }}"
                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                    placeholder="Auth Code2">
                            </div>
                        </div>

                        <!-- Divider: Address -->
                        <div class="mb-3">
                            <p
                                class="text-xs font-semibold text-gray-600 mb-2 bg-gradient-to-r from-gray-200 to-gray-100 px-2 py-1">
                                Address Details</p>
                            <div>
                                <label class="block text-gray-700 font-semibold text-xs mb-1">Address</label>
                                <textarea name="address" rows="2"
                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                    placeholder="Street / Colony / Area">{{ old('address') }}</textarea>
                                @error('address')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-6 gap-3 mb-3">
                                <!-- Country (String Input) -->
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Country</label>
                                    <input type="text" name="country" value="{{ old('country') }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                        placeholder="e.g., India">
                                    @error('country')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- State (String Input) -->
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">State</label>
                                    <input type="text" name="state" value="{{ old('state') }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                        placeholder="e.g., Tamil Nadu">
                                    @error('state')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">GST No</label>
                                    <input type="text" name="gst_number" value="{{ old('gst_number') }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                        placeholder="GST Number">
                                    @error('gst_number')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- PAN No -->
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">PAN No</label>
                                    <input type="text" name="pan_number" value="{{ old('pan_number') }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                        placeholder="PAN Number">
                                    @error('pan_number')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- TAN No -->
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">TAN No</label>
                                    <input type="text" name="tan_no" value="{{ old('tan_no') }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                        placeholder="TAN Number">
                                    @error('tan_no')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- MSME No -->
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">MSME No</label>
                                    <input type="text" name="msme_no" value="{{ old('msme_no') }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                        placeholder="MSME Number">
                                    @error('msme_no')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                        </div>



                        <!-- Divider: Contact Details -->
                        <div class="mb-3">
                            <p
                                class="text-xs font-semibold text-gray-600 mb-2 bg-gradient-to-r from-gray-200 to-gray-100 px-2 py-1">
                                Contact Details</p>
                            <div class="grid grid-cols-1 md:grid-cols-[1fr_1fr_1fr_1fr_1fr_1fr_1fr_auto] gap-3">
                                <!-- Contact Name -->
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Name</label>
                                    <input type="text" name="contact_name[]" value="{{ old('contact_name.0') }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                        placeholder="Contact name">
                                    @error('contact_name.0')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- Designation -->
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Designation</label>
                                    <input type="text" name="designation[]" value="{{ old('designation.0') }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                        placeholder="Designation">
                                    @error('designation.0')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- Email -->
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Email</label>
                                    <input type="email" name="email[]" value="{{ old('email.0') }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                        placeholder="email@example.com">
                                    @error('email.0')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- Mobile -->
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Mobile</label>
                                    <input type="text" name="mobile[]" value="{{ old('mobile.0') }}" maxlength="10"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                        placeholder="Mobile number">
                                    @error('mobile.0')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- Fax -->
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Fax</label>
                                    <input type="text" name="faxnumber[]" value="{{ old('faxnumber.0') }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                        placeholder="Fax number">
                                    @error('faxnumber.0')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- Website -->
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Website</label>
                                    <input type="text" name="website[]" value="{{ old('website.0') }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                        placeholder="Website URL">
                                    @error('website.0')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Location</label>
                                    <input type="text" name="location[]" value="{{ old('location.0') }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                        placeholder="Location">
                                    @error('location.0')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <button type="button" class="btn-primary mt-5" onclick="addContact()">
                                        <i data-lucide="plus" class="h-4 w-4"></i>
                                    </button>
                                </div>

                            </div>
                            <div id="contacts-container">
                                @if (old('contact_name') && count(old('contact_name')) > 1)
                                    @for ($i = 1; $i < count(old('contact_name')); $i++)
                                        <div
                                            class="grid grid-cols-1 md:grid-cols-[1fr_1fr_1fr_1fr_1fr_1fr_1fr_auto] gap-3 mt-2">
                                            <div>
                                                <input type="text" name="contact_name[]"
                                                    value="{{ old('contact_name.' . $i) }}"
                                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                                    placeholder="Contact name" required>
                                                @error('contact_name.' . $i)
                                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <input type="text" name="designation[]"
                                                    value="{{ old('designation.' . $i) }}"
                                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                                    placeholder="Designation" required>
                                                @error('designation.' . $i)
                                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <input type="email" name="email[]" value="{{ old('email.' . $i) }}"
                                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                                    placeholder="email@example.com" required>
                                                @error('email.' . $i)
                                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <input type="text" name="mobile[]" value="{{ old('mobile.' . $i) }}"
                                                    maxlength="10"
                                                    oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                                    placeholder="Mobile number" required>
                                                @error('mobile.' . $i)
                                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <input type="text" name="faxnumber[]"
                                                    value="{{ old('faxnumber.' . $i) }}"
                                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                                    placeholder="Fax number">
                                                @error('faxnumber.' . $i)
                                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <input type="text" name="website[]"
                                                    value="{{ old('website.' . $i) }}"
                                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                                    placeholder="Website URL">
                                                @error('website.' . $i)
                                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <input type="text" name="location[]"
                                                    value="{{ old('location.' . $i) }}"
                                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                                    placeholder="Location" required>
                                                @error('location.' . $i)
                                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <button type="button" class="btn-danger" onclick="removeContact(this)"><i
                                                    data-lucide="trash" class="h-4 w-4"></i></button>
                                        </div>
                                    @endfor
                                @endif
                            </div>

                        </div>
                        <div class="mb-3">
                            <p
                                class="text-xs font-semibold text-gray-600 mb-2 bg-gradient-to-r from-gray-200 to-gray-100 px-2 py-1">
                                Tally Details</p>
                            <div class="grid grid-cols-1 md:grid-cols-6 gap-3 mb-2">
                                <!-- Tally Serial No -->
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Tally Serial No</label>
                                    <input type="text" value="{{ old('tally_serial') }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs bg-gray-100 cursor-not-allowed"
                                        placeholder="Tally serial no" disabled>
                                    <input type="hidden" name="tally_serial" value="{{ old('tally_serial') }}">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Tally Version</label>
                                    <input type="text" value="{{ old('tally_version') }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs bg-gray-100 cursor-not-allowed"
                                        placeholder="Tally version" disabled>
                                    <input type="hidden" name="tally_version" value="{{ old('tally_version') }}">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Tally Release</label>
                                    <input type="text" value="{{ old('tally_release') }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs bg-gray-100 cursor-not-allowed"
                                        placeholder="Tally release" disabled>
                                    <input type="hidden" name="tally_release" value="{{ old('tally_release') }}">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Tally Expiry</label>
                                    <div class="relative">
                                        <input type="text" value="{{ old('tally_expiry') }}"
                                            class="w-full pl-2 pr-8 py-1 border border-gray-300 rounded text-xs bg-gray-100 cursor-not-allowed"
                                            placeholder="Tally expiry" disabled>
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-gray-400">
                                            {{-- <i data-lucide="calendar" class="w-4 h-4"></i> --}}
                                        </div>
                                    </div>
                                    <input type="hidden" name="tally_expiry" value="{{ old('tally_expiry') }}">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Tally Edition</label>
                                    <input type="text" value="{{ old('tally_edition') }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs bg-gray-100 cursor-not-allowed"
                                        placeholder="Tally edition" disabled>
                                    <input type="hidden" name="tally_edition" value="{{ old('tally_edition') }}">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Tally Net Id</label>
                                    <input type="text" value="{{ old('tally_net_id') }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs bg-gray-100 cursor-not-allowed"
                                        placeholder="Tally net id" disabled>
                                    <input type="hidden" name="tally_net_id" value="{{ old('tally_net_id') }}">
                                </div>

                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                                <!-- Tally Users -->
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Tally No of Users
                                        Editable</label>
                                    <input type="text" name="tally_users" value="{{ old('tally_users') }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                        placeholder="Tally no of users editable">
                                    @error('tally_users')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- Tally Deployed -->
                                <div>
                                    <div class="grid grid-cols-1 md:grid-cols-[auto_1fr]">
                                        <div class="pr-3">
                                            <label class="block text-gray-700 font-semibold text-xs mb-1">Tally Deployed
                                                on</label>
                                            <select name="tally_deployed" id="tally_deployed"
                                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600 select2-basic"
                                                onchange="handleTallyDeployedChange(this)">
                                                @foreach ($deploymentOptions as $value => $label)
                                                    <option value="{{ $value }}"
                                                        {{ old('tally_deployed', 'cloud') === $value ? 'selected' : '' }}>
                                                        {{ $label }}</option>
                                                @endforeach
                                            </select>
                                            @error('tally_deployed')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div id="no_of_computers_container" class="hidden">
                                            <label class="block text-gray-700 font-semibold text-xs mb-1 text-end">No. of
                                                Computers</label>
                                            <input type="text" name="no_of_computers"
                                                value="{{ old('no_of_computers') }}"
                                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600">
                                        </div>
                                        <div id="existing_provider_container" class="hidden">
                                            <label class="block text-gray-700 font-semibold text-xs mb-1 text-end">Existing
                                                Service Provider</label>
                                            <input type="text" name="existing_provider"
                                                value="{{ old('existing_provider') }}"
                                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600">
                                        </div>
                                    </div>
                                </div>
                                <!-- Tally Data Volume -->
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Tally Data Volume</label>
                                    <input type="text" name="tally_data_volume"
                                        value="{{ old('tally_data_volume') }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                        placeholder="Tally Data Volume">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Tally Cloud
                                        Opportunity</label>
                                    <select name="tally_cloud" id="tally_cloud"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600 select2-basic">
                                        <option value="1" {{ old('tally_cloud', '1') === '1' ? 'selected' : '' }}>Yes
                                        </option>
                                        <option value="0" {{ old('tally_cloud', '0') === '0' ? 'selected' : '' }}>No
                                        </option>
                                    </select>
                                    @error('tally_cloud')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>


                            </div>

                        </div>
                        <div class="mb-3">
                            <p
                                class="text-xs font-semibold text-gray-600 mb-2 bg-gradient-to-r from-gray-200 to-gray-100 px-2 py-1">
                                Additional Details</p>
                            <div class="grid grid-cols-1 md:grid-cols-7 gap-3">
                                <!-- Last Sync Date -->
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Last Sync Date</label>
                                    <div class="relative">
                                        <input type="text" name="last_sync_date" value="{{ old('last_sync_date') }}"
                                            class="datepicker w-full pl-2 pr-8 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600">
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-gray-400">
                                            <i data-lucide="calendar" class="w-4 h-4"></i>
                                        </div>
                                    </div>
                                </div>
                                <!-- Rollout Request Date -->
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Date of Rollout
                                        Request</label>
                                    <div class="relative">
                                        <input type="text" name="rollout_request_date"
                                            value="{{ old('rollout_request_date') }}"
                                            class="datepicker w-full pl-2 pr-8 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600">
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-gray-400">
                                            <i data-lucide="calendar" class="w-4 h-4"></i>
                                        </div>
                                    </div>
                                </div>
                                <!-- TCP Generated Date -->
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Date of TCP
                                        Generated</label>
                                    <div class="relative">
                                        <input type="text" name="tcp_generated_date"
                                            value="{{ old('tcp_generated_date') }}"
                                            class="datepicker w-full pl-2 pr-8 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600">
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-gray-400">
                                            <i data-lucide="calendar" class="w-4 h-4"></i>
                                        </div>
                                    </div>
                                </div>
                                <!-- Rollout Done Date -->
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Date of Rollout
                                        Done</label>
                                    <div class="relative">
                                        <input type="text" name="rollout_done_date"
                                            value="{{ old('rollout_done_date') }}"
                                            class="datepicker w-full pl-2 pr-8 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600">
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-gray-400">
                                            <i data-lucide="calendar" class="w-4 h-4"></i>
                                        </div>
                                    </div>
                                </div>
                                <!-- Rollout Done By -->
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Rollout Done By</label>
                                    <input type="text" name="rollout_done_by" value="{{ old('rollout_done_by') }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600">
                                </div>
                                <!-- Rollout Remarks -->
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Remarks of
                                        rollout</label>
                                    <input type="text" name="rollout_remarks" value="{{ old('rollout_remarks') }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Remarks Date</label>
                                    <div class="relative">
                                        <input type="text" name="remarks_date" value="{{ old('remarks_date') }}"
                                            class="datepicker w-full pl-2 pr-8 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600">
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-gray-400">
                                            <i data-lucide="calendar" class="w-4 h-4"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <p
                                class="text-xs font-semibold text-gray-600 mb-2 bg-gradient-to-r from-gray-200 to-gray-100 px-2 py-1">
                                Sync Information</p>
                            <div class="mb-2">
                                <label class="block text-gray-700 font-semibold text-xs mb-1">No of Sync URLs</label>
                                <select id="no_of_sync_urls_dropdown" name="no_of_sync_urls"
                                    class="w-1/4 px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                    onchange="generateSyncUrlInputs()">
                                    <option value="0">-- Select Count --</option>
                                    @for ($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}" {{ old('no_of_sync_urls') == $i ? 'selected' : '' }}>
                                            {{ $i }}</option>
                                    @endfor
                                </select>
                                @error('no_of_sync_urls')
                                    <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div id="sync-urls-container" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @php
                                    $oldUrls = old('sync_urls', []);
                                @endphp
                                @foreach($oldUrls as $idx => $url)
                                    <div class="p-2 border border-gray-200 rounded bg-gray-50 sync-url-block relative">
                                        <label class="block text-gray-700 font-semibold text-[10px] mb-1">Sync URL {{ $idx + 1 }}</label>
                                        <input type="text" name="sync_urls[]" value="{{ $url }}"
                                            class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                            placeholder="https://example.com/api">
                                        @error('sync_urls.'.$idx)
                                            <p class="text-red-500 text-[10px] mt-0.5">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <!-- Buttons -->
                    <div class="flex items-center justify-end space-x-2 px-4 py-2 border-t border-gray-200 ">
                        <x-secondary-button type="reset">
                            <i data-lucide="refresh-cw" class="h-4 w-4"></i> Reset
                        </x-secondary-button>
                        <x-primary-button type="submit" id="submitBtn" onclick="setLoading(this)"
                            class="whitespace-nowrap">
                            <span class="submit-text flex items-center gap-1">
                                <i data-lucide="save" class="h-4 w-4"></i> Submit
                            </span>
                            <span class="submit-loader hidden flex items-center gap-1">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
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
    </div>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initSelect2();

            // Initialize Flatpickr for dates
            flatpickr(".datepicker", {
                dateFormat: "d/m/Y",
                allowInput: true
            });

            // If company is already selected (e.g. after validation error)
            const initialCompany = $('#company_code').val();
            if (initialCompany) {
                fetchCompanyDetails(initialCompany, "{{ old('type') }}");
            }

            // Restore chained geo on validation error

            const oldRegion = '{{ old('region') }}';
            const oldState = '{{ old('state') }}';
            const oldCity = '{{ old('city') }}';

            // Tally Deployed on init
            const tallyDeployed = $('#tally_deployed').val();
            if (tallyDeployed === 'local') {
                $('#no_of_computers_container').removeClass('hidden');
                $('#existing_provider_container').addClass('hidden');
            } else {
                $('#no_of_computers_container').addClass('hidden');
                $('#existing_provider_container').removeClass('hidden');
            }
        });

        function initSelect2() {
            $('.select2-basic').select2({
                allowClear: false,
                width: '100%',
                containerCssClass: 'text-xs',
                selectionCssClass: 'text-xs'
            });
        }

        // ─── Geo Chained Dropdowns ───────────────────────────────────────────────


        $('#region').on('change', function() {
            const regionPid = $(this).val();
            resetSelect('state', '-- Select State --');
            resetSelect('city', '-- Select City --');
            if (!regionPid) return;
            loadStates(regionPid);
        });

        $('#state').on('change', function() {
            const statePid = $(this).val();
            resetSelect('city', '-- Select City --');
            if (!statePid) return;
            loadCities(statePid);
        });

        function resetSelect(id, placeholder) {
            $('#' + id).html('<option value="">' + placeholder + '</option>').trigger('change.select2');
        }



        function loadStates(regionPid, preselectPid = null, callback = null) {
            $('#state-loader').removeClass('hidden');
            $.get(`/distributors/geo/states/${regionPid}`, function(data) {
                let html = '<option value="">-- Select State --</option>';
                data.forEach(function(item) {
                    const sel = (preselectPid && item.id == preselectPid) ? 'selected' : '';
                    html += `<option value="${item.id}" ${sel}>${item.name}</option>`;
                });
                $('#state').html(html).trigger('change.select2');
                if (preselectPid) $('#state').val(preselectPid).trigger('change.select2');
                if (callback) callback();
            }).always(function() {
                $('#state-loader').addClass('hidden');
            });
        }

        function loadCities(statePid, preselectPid = null) {
            $('#city-loader').removeClass('hidden');
            $.get(`/distributors/geo/cities/${statePid}`, function(data) {
                let html = '<option value="">-- Select City --</option>';
                data.forEach(function(item) {
                    const sel = (preselectPid && item.id == preselectPid) ? 'selected' : '';
                    html += `<option value="${item.id}" ${sel}>${item.name}</option>`;
                });
                $('#city').html(html).trigger('change.select2');
                if (preselectPid) $('#city').val(preselectPid).trigger('change.select2');
            }).always(function() {
                $('#city-loader').addClass('hidden');
            });
        }

        // ─── Tally Deployed ──────────────────────────────────────────────────────

        function handleTallyDeployedChange(selectElement) {
            const selectedValue = selectElement.value;
            if (selectedValue === 'local') {
                $('#no_of_computers_container').removeClass('hidden');
                $('#existing_provider_container').addClass('hidden');
            } else {
                $('#no_of_computers_container').addClass('hidden');
                $('#existing_provider_container').removeClass('hidden');
            }
        }

        // ─── Company Details ─────────────────────────────────────────────────────

        function fetchCompanyDetails(companyPid, selectedType = null) {
            if (!companyPid) {
                $('#type').html('<option value="">-- Select Type --</option>').trigger('change.select2');
                $('#parameters-section').addClass('hidden');
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
                    $('#type').html(typeHtml).trigger('change.select2');

                    // Populate Parameters
                    let hasParams = false;
                    for (let i = 1; i <= 10; i++) {
                        const label = data.parameters[i - 1];
                        if (label) {
                            $(`#param-label-${i}`).text(label);
                            $(`#param-group-${i}`).removeClass('hidden');
                            hasParams = true;
                        } else {
                            $(`#param-group-${i}`).addClass('hidden');
                        }
                    }

                    $('#parameters-section').toggleClass('hidden', !hasParams);
                    lucide.createIcons();
                },
                error: function() {
                    console.error('Failed to fetch company details');
                }
            });
        }

        // ─── Contact Rows ────────────────────────────────────────────────────────

        function addContact() {
            const container = document.getElementById('contacts-container');
            const div = document.createElement('div');
            div.className = "grid grid-cols-1 md:grid-cols-[1fr_1fr_1fr_1fr_1fr_auto] gap-3 mt-2";
            div.innerHTML = `
                            <div><input type="text" name="contact_name[]" value="" class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600" placeholder="Contact name" required></div>
                            <div><input type="text" name="designation[]" value="" class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600" placeholder="Designation" required></div>
                            <div><input type="email" name="email[]" value="" class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600" placeholder="email@example.com" required></div>
                            <div><input type="text" name="mobile[]" value="" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');" class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600" placeholder="Mobile number" required></div>
                            <div><input type="text" name="location[]" value="" class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600" placeholder="Location" required></div>
                            <button type="button" class="btn-danger" onclick="removeContact(this)"><i data-lucide="trash" class="h-4 w-4"></i></button>
                        `;
            container.appendChild(div);
            lucide.createIcons();
        }

        function removeContact(button) {
            const div = button.parentElement;
            div.remove();
        }

        function setLoading(btn) {
            btn.querySelector('.submit-text').classList.add('hidden');
            btn.querySelector('.submit-loader').classList.remove('hidden');
            btn.disabled = true;
            btn.closest('form').submit();
        }

        // ─── Sync URL Rows ────────────────────────────────────────────────────────

        function generateSyncUrlInputs() {
            const count = parseInt(document.getElementById('no_of_sync_urls_dropdown').value);
            const container = document.getElementById('sync-urls-container');
            const currentBlocks = container.querySelectorAll('.sync-url-block');
            const currentCount = currentBlocks.length;

            if (count < currentCount) {
                if (!confirm(`Are you sure you want to remove ${currentCount - count} Sync URL(s)?`)) {
                    document.getElementById('no_of_sync_urls_dropdown').value = currentCount;
                    return;
                }
                for (let i = currentCount - 1; i >= count; i--) {
                    currentBlocks[i].remove();
                }
            } else if (count > currentCount) {
                for (let i = currentCount; i < count; i++) {
                    const div = document.createElement('div');
                    div.className = "p-2 border border-gray-200 rounded bg-gray-50 sync-url-block relative";
                    div.innerHTML = `
                        <label class="block text-gray-700 font-semibold text-[10px] mb-1">Sync URL ${i + 1}</label>
                        <input type="text" name="sync_urls[]"
                            class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                            placeholder="https://example.com/api">
                    `;
                    container.appendChild(div);
                }
            }
        }
    </script>
@endsection
