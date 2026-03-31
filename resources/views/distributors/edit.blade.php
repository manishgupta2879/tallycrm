@extends('layouts.app', ['breadcrumb' => 'Distributor', 'breadcrumbRight' => 'Dashboard->Primary Setup->Distributor'])

@section('content')
    <div class="max-w-full">

        <div class="bg-white rounded shadow-sm border border-gray-200">
            <div class="flex items-center justify-between py-2 px-3 border-b border-gray-200">
                <h6>Edit Distributor</h6>
                <div class="flex items-center space-x-1">
                    {{-- <a href="{{ route('distributors.tally-details', $distributor->id) }}" class="btn-secondary">
                        <i data-lucide="clipboard-list" class="h-4 w-4"></i> Tally Details
                    </a> --}}
                    <a href="{{ route('distributors.tdl-addons', $distributor->id) }}" class="btn-secondary">
                        <i data-lucide="puzzle" class="h-4 w-4"></i> TDL Addons
                    </a>
                    <a href="{{ route('distributors.company-features', $distributor->id) }}" class="btn-secondary">
                        <i data-lucide="settings-2" class="h-4 w-4"></i> Company Features
                    </a>
                    <a href="{{ route('distributors.index') }}" class="btn-secondary">
                        <i data-lucide="list" class="h-4 w-4"></i> Distributor List
                    </a>
                </div>
            </div>

            <div>

                <form action="{{ route('distributors.update', $distributor->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Row 1: Code, Name, Company, Type -->

                    <div class="overflow-x-auto px-4 relative" style="max-height: calc(100vh - 263px);">


                        <div class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-3 pt-2 sticky top-0 bg-white z-10 transition-shadow"
                            id="stickyHeader">
                            <!-- Distributor Code -->
                            <div>
                                <label class="block text-gray-700 font-semibold text-xs mb-1">
                                    Distributor Code<span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="code" value="{{ old('code', $distributor->code) }}" required
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
                                <input type="text" name="name" value="{{ old('name', $distributor->name) }}" required
                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                    placeholder="Distributor name">
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold text-xs mb-1">Distributor
                                    Password</label>
                                <input type="text" name="dist_perm_pass"
                                    value="{{ old('dist_perm_pass', $distributor->dist_perm_pass) }}"
                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                    placeholder="Distributor Password">
                            </div>
                            <!-- Company -->
                            <div>
                                <label class="block text-gray-700 font-semibold text-xs mb-1">Principal Company<span
                                        class="text-red-500">*</span></label>
                                <select name="company_code" id="company_code" required
                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600 select2-basic"
                                    onchange="fetchCompanyDetails(this.value)">
                                    <option value="">-- Select Company --</option>
                                    @foreach ($companies as $comp)
                                        <option value="{{ $comp->pid }}"
                                            {{ old('company_code', $distributor->company_code) === $comp->pid ? 'selected' : '' }}>
                                            {{ $comp->pid }} - {{ $comp->name }}
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
                                    @if ($distributor->type)
                                        <option value="{{ $distributor->type }}" selected>
                                            {{ $distributor->type }}
                                        </option>
                                    @endif
                                </select>
                                @error('type')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Dynamic Parameters -->
                        <div id="parameters-section" class="mb-3 hidden">
                            <p
                                class="text-xs font-semibold text-gray-600 mb-2 bg-gradient-to-r from-gray-200 to-gray-100 px-2 py-1">
                                Additional Parameters
                            </p>
                            <div id="parameters-container" class="grid grid-cols-1 md:grid-cols-5 gap-3">
                                @for ($i = 1; $i <= 10; $i++)
                                    <div id="param-group-{{ $i }}" class="hidden">
                                        <label id="param-label-{{ $i }}"
                                            class="block text-gray-700 font-semibold text-xs mb-1"></label>
                                        <input type="text" name="d_parameter_{{ $i }}"
                                            value="{{ old('d_parameter_' . $i, $distributor->params[$i] ?? '') }}"
                                            class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600">
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <!-- Address Details -->
                        <div class="mb-3">
                            <p
                                class="text-xs font-semibold text-gray-600 mb-2 bg-gradient-to-r from-gray-200 to-gray-100 px-2 py-1">
                                Address Details
                            </p>
                            <div>
                                <label class="block text-gray-700 font-semibold text-xs mb-1">Address</label>
                                <textarea name="address" rows="2"
                                    class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                    placeholder="Street / Colony / Area">{{ old('address', $distributor->address) }}</textarea>
                                @error('address')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-3">
                                <!-- Country -->
                                <div>
                                    <label for="country"
                                        class="block text-gray-700 font-semibold text-xs mb-1">Country</label>
                                    <select name="country" id="country"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600 select2-basic">
                                        <option value="">-- Select Country --</option>
                                        @foreach ($countries as $c)
                                            <option value="{{ $c->id }}"
                                                {{ old('country', $distributor->country) == $c->id ? 'selected' : '' }}>
                                                {{ $c->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('country')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Region -->
                                <div>
                                    <label for="region" class="block text-gray-700 font-semibold text-xs mb-1">
                                        Region
                                        <span id="region-loader" class="hidden ml-1 inline-block">
                                            <svg class="animate-spin h-3 w-3 text-blue-500 inline"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                        </span>
                                    </label>
                                    <select name="region" id="region"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600 select2-basic">
                                        <option value="">-- Select Region --</option>
                                    </select>
                                    @error('region')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- State -->
                                <div>
                                    <label for="state" class="block text-gray-700 font-semibold text-xs mb-1">
                                        State
                                        <span id="state-loader" class="hidden ml-1 inline-block">
                                            <svg class="animate-spin h-3 w-3 text-blue-500 inline"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                        </span>
                                    </label>
                                    <select name="state" id="state"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600 select2-basic">
                                        <option value="">-- Select State --</option>
                                    </select>
                                    @error('state')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- City -->
                                <div>
                                    <label for="city" class="block text-gray-700 font-semibold text-xs mb-1">
                                        City
                                        <span id="city-loader" class="hidden ml-1 inline-block">
                                            <svg class="animate-spin h-3 w-3 text-blue-500 inline"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                        </span>
                                    </label>
                                    <select name="city" id="city"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600 select2-basic">
                                        <option value="">-- Select City --</option>
                                    </select>
                                    @error('city')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Pincode -->
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Pin Code</label>
                                    <input type="text" name="pincode"
                                        value="{{ old('pincode', $distributor->pincode) }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                        placeholder="Pin Code">
                                    @error('pincode')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- GST Number -->
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">GST No</label>
                                    <input type="text" name="gst_number"
                                        value="{{ old('gst_number', $distributor->gst_number) }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                        placeholder="GST Number">
                                    @error('gst_number')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- PAN Number -->
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">PAN No</label>
                                    <input type="text" name="pan_number"
                                        value="{{ old('pan_number', $distributor->pan_number) }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                        placeholder="PAN Number">
                                    @error('pan_number')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- TAN No -->
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">TAN No</label>
                                    <input type="text" name="tan_no" value="{{ old('tan_no', $distributor->tan_no) }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                        placeholder="TAN Number">
                                    @error('tan_no')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- MSME No -->
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">MSME No</label>
                                    <input type="text" name="msme_no" value="{{ old('msme_no', $distributor->msme_no) }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                        placeholder="MSME Number">
                                    @error('msme_no')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>



                        <!-- Contact Details -->
                        <div class="mb-3">
                            <p
                                class="text-xs font-semibold text-gray-600 mb-2 bg-gradient-to-r from-gray-200 to-gray-100 px-2 py-1">
                                Contact Details
                            </p>
                            @php
                                $contacts = $distributor->contacts;
                                $count = max(1, count($contacts));
                            @endphp
                            <div class="grid grid-cols-1 md:grid-cols-[1fr_1fr_1fr_1fr_1fr_auto] gap-3">
                                <div><label class="block text-gray-700 font-semibold text-xs mb-1">Name</label>
                                    <input type="text" name="contact_name[]"
                                        value="{{ old('contact_name.0', $contacts[0]->name ?? '') }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                        placeholder="Contact name">
                                    @error('contact_name.0')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div><label class="block text-gray-700 font-semibold text-xs mb-1">Designation</label>
                                    <input type="text" name="designation[]"
                                        value="{{ old('designation.0', $contacts[0]->desig ?? '') }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                        placeholder="Designation">
                                    @error('designation.0')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div><label class="block text-gray-700 font-semibold text-xs mb-1">Email</label>
                                    <input type="email" name="email[]"
                                        value="{{ old('email.0', $contacts[0]->email ?? '') }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                        placeholder="email@example.com">
                                    @error('email.0')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div><label class="block text-gray-700 font-semibold text-xs mb-1">Mobile</label>
                                    <input type="text" name="mobile[]"
                                        value="{{ old('mobile.0', $contacts[0]->mobile ?? '') }}" maxlength="10"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                        placeholder="Mobile number">
                                    @error('mobile.0')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div><label class="block text-gray-700 font-semibold text-xs mb-1">Location</label>
                                    <input type="text" name="location[]"
                                        value="{{ old('location.0', $contacts[0]->loc ?? '') }}"
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
                                @for ($ci = 1; $ci < $count; $ci++)
                                    <div class="grid grid-cols-1 md:grid-cols-[1fr_1fr_1fr_1fr_1fr_auto] gap-3 mt-2">
                                        <div><input type="text" name="contact_name[]"
                                                value="{{ old('contact_name.' . $ci, $contacts[$ci]->name ?? '') }}"
                                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                                placeholder="Contact name">
                                            @error('contact_name.' . $ci)
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div><input type="text" name="designation[]"
                                                value="{{ old('designation.' . $ci, $contacts[$ci]->desig ?? '') }}"
                                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                                placeholder="Designation">
                                            @error('designation.' . $ci)
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div><input type="email" name="email[]"
                                                value="{{ old('email.' . $ci, $contacts[$ci]->email ?? '') }}"
                                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                                placeholder="email@example.com">
                                            @error('email.' . $ci)
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div><input type="text" name="mobile[]"
                                                value="{{ old('mobile.' . $ci, $contacts[$ci]->mobile ?? '') }}"
                                                maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                                placeholder="Mobile number">
                                            @error('mobile.' . $ci)
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div><input type="text" name="location[]"
                                                value="{{ old('location.' . $ci, $contacts[$ci]->loc ?? '') }}"
                                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                                placeholder="Location">
                                            @error('location.' . $ci)
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <button type="button" class="btn-danger" onclick="removeContact(this)">
                                            <i data-lucide="trash" class="h-4 w-4"></i>
                                        </button>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <!-- Tally Details -->
                        <div class="mb-3">
                            <p
                                class="text-xs font-semibold text-gray-600 mb-2 bg-gradient-to-r from-gray-200 to-gray-100 px-2 py-1">
                                Tally Details
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                                <!-- Tally Serial — Disabled, hidden carries value -->
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Tally Serial No</label>
                                    <input type="text" value="{{ old('tally_serial', $distributor->tally_serial) }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs bg-gray-100 cursor-not-allowed"
                                        disabled>
                                    <input type="hidden" name="tally_serial"
                                        value="{{ old('tally_serial', $distributor->tally_serial) }}">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Tally Version</label>
                                    <input type="text" value="{{ old('tally_version', $distributor->tally_version) }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs bg-gray-100 cursor-not-allowed"
                                        disabled>
                                    <input type="hidden" name="tally_version"
                                        value="{{ old('tally_version', $distributor->tally_version) }}">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Tally Release</label>
                                    <input type="text" value="{{ old('tally_release', $distributor->tally_release) }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs bg-gray-100 cursor-not-allowed"
                                        disabled>
                                    <input type="hidden" name="tally_release"
                                        value="{{ old('tally_release', $distributor->tally_release) }}">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Tally Expiry</label>
                                    <div class="relative">
                                        <input type="text"
                                            value="{{ old('tally_expiry', optional($distributor->tally_expiry)->format('d/m/Y')) }}"
                                            class="w-full pl-2 pr-8 py-1 border border-gray-300 rounded text-xs bg-gray-100 cursor-not-allowed"
                                            disabled>
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-gray-400">
                                            <i data-lucide="calendar" class="w-4 h-4"></i>
                                        </div>
                                    </div>
                                    <input type="hidden" name="tally_expiry"
                                        value="{{ old('tally_expiry', $distributor->tally_expiry) }}">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Tally Edition</label>
                                    <input type="text" value="{{ old('tally_edition', $distributor->tally_edition) }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs bg-gray-100 cursor-not-allowed"
                                        disabled>
                                    <input type="hidden" name="tally_edition"
                                        value="{{ old('tally_edition', $distributor->tally_edition) }}">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Tally Net Id</label>
                                    <input type="text" value="{{ old('tally_net_id', $distributor->tally_net_id) }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs bg-gray-100 cursor-not-allowed"
                                        disabled>
                                    <input type="hidden" name="tally_net_id"
                                        value="{{ old('tally_net_id', $distributor->tally_net_id) }}">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">TCP Version</label>
                                    <input type="text" value="{{ old('tcp_version', $distributor->tcp_version) }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs bg-gray-100 cursor-not-allowed"
                                        disabled>
                                    <input type="hidden" name="tcp_version"
                                        value="{{ old('tcp_version', $distributor->tcp_version) }}">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">TCP Source</label>
                                    <input type="text" value="{{ old('tcp_source', $distributor->tcp_source) }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs bg-gray-100 cursor-not-allowed"
                                        disabled>
                                    <input type="hidden" name="tcp_source"
                                        value="{{ old('tcp_source', $distributor->tcp_source) }}">
                                </div>
                                <!-- Tally Users -->
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Tally No of Users
                                        Editable</label>
                                    <input type="text" name="tally_users"
                                        value="{{ old('tally_users', $distributor->tally_users) }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                        placeholder="No. of users">
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
                                                        {{ old('tally_deployed', $distributor->tally_deployed) === $value ? 'selected' : '' }}>
                                                        {{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div id="no_of_computers_container"
                                            class="{{ old('tally_deployed', $distributor->tally_deployed) === 'local' ? '' : 'hidden' }}">
                                            <label class="block text-gray-700 font-semibold text-xs mb-1 text-end">No. of
                                                Computers</label>
                                            <input type="text" name="no_of_computers"
                                                value="{{ old('no_of_computers', $distributor->no_of_computers) }}"
                                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600">
                                        </div>
                                        <div id="existing_provider_container"
                                            class="{{ old('tally_deployed', $distributor->tally_deployed) !== 'local' ? '' : 'hidden' }}">
                                            <label class="block text-gray-700 font-semibold text-xs mb-1 text-end">Existing
                                                Service Provider</label>
                                            <input type="text" name="existing_provider"
                                                value="{{ old('existing_provider', $distributor->existing_provider) }}"
                                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600">
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Tally Data Volume</label>
                                    <input type="text" name="tally_data_volume"
                                        value="{{ old('tally_data_volume', $distributor->tally_data_volume) }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                        placeholder="Tally Data Volume">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Tally Cloud
                                        Opportunity</label>
                                    <select name="tally_cloud" id="tally_cloud"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600 select2-basic">
                                        <option value="1"
                                            {{ old('tally_cloud', $distributor->tally_cloud) == 1 ? 'selected' : '' }}>Yes
                                        </option>
                                        <option value="0"
                                            {{ old('tally_cloud', $distributor->tally_cloud) == 0 ? 'selected' : '' }}>No
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Details -->
                        <div class="mb-3">
                            <p
                                class="text-xs font-semibold text-gray-600 mb-2 bg-gradient-to-r from-gray-200 to-gray-100 px-2 py-1">
                                Additional Details
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Last Sync Date</label>
                                    <div class="relative">
                                        <input type="text" name="last_sync_date"
                                            value="{{ old('last_sync_date', optional($distributor->last_sync_date)->format('d/m/Y')) }}"
                                            class="datepicker w-full pl-2 pr-8 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600">
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-gray-400">
                                            <i data-lucide="calendar" class="w-4 h-4"></i>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Date of Rollout Request
                                        Receive</label>
                                    <div class="relative">
                                        <input type="text" name="rollout_request_date"
                                            value="{{ old('rollout_request_date', optional($distributor->rollout_request_date)->format('d/m/Y')) }}"
                                            class="datepicker w-full pl-2 pr-8 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600">
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-gray-400">
                                            <i data-lucide="calendar" class="w-4 h-4"></i>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Date of TCP
                                        Generated</label>
                                    <div class="relative">
                                        <input type="text" name="tcp_generated_date"
                                            value="{{ old('tcp_generated_date', optional($distributor->tcp_generated_date)->format('d/m/Y')) }}"
                                            class="datepicker w-full pl-2 pr-8 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600">
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-gray-400">
                                            <i data-lucide="calendar" class="w-4 h-4"></i>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Date of Rollout
                                        Done</label>
                                    <div class="relative">
                                        <input type="text" name="rollout_done_date"
                                            value="{{ old('rollout_done_date', optional($distributor->rollout_done_date)->format('d/m/Y')) }}"
                                            class="datepicker w-full pl-2 pr-8 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600">
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-gray-400">
                                            <i data-lucide="calendar" class="w-4 h-4"></i>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Rollout Done By</label>
                                    <input type="text" name="rollout_done_by"
                                        value="{{ old('rollout_done_by', $distributor->rollout_done_by) }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Remarks of
                                        rollout</label>
                                    <input type="text" name="rollout_remarks"
                                        value="{{ old('rollout_remarks', $distributor->rollout_remarks) }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Remarks Date</label>
                                    <div class="relative">
                                        <input type="text" name="remarks_date"
                                            value="{{ old('remarks_date', optional($distributor->remarks_date)->format('d/m/Y')) }}"
                                            class="datepicker w-full pl-2 pr-8 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600">
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-gray-400">
                                            <i data-lucide="calendar" class="w-4 h-4"></i>
                                        </div>
                                    </div>
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
                                                {{ old('status', $distributor->status) === $option ? 'selected' : '' }}>
                                                {{ $option }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <!-- Sync Information -->
                        <div class="mb-3">
                            <p
                                class="text-xs font-semibold text-gray-600 mb-2 bg-gradient-to-r from-gray-200 to-gray-100 px-2 py-1">
                                Sync Information
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">


                                <div class="col-span-3">
                                    <label class="block text-gray-700 font-semibold text-xs mb-1">Sync URLs</label>
                                    <div id="sync-urls-container" class="space-y-1">
                                        @php
                                            $syncurls = is_array($distributor->c_urls) ? $distributor->c_urls : [];
                                            $count = max(1, count($syncurls));
                                        @endphp
                                        @for ($i = 0; $i < $count; $i++)
                                            <div class="flex items-center space-x-1 sync-url-row">
                                                <input type="text" name="sync_urls[]"
                                                    value="{{ old('sync_urls.' . $i, $syncurls[$i] ?? '') }}"
                                                    class="flex-1 px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                                    placeholder="https://example.com/api">
                                                @if ($i === 0)
                                                    <button type="button" class="btn-primary px-2 py-1 rounded"
                                                        onclick="addSyncUrl()">
                                                        <i data-lucide="plus" class="h-3.5 w-3.5"></i>
                                                    </button>
                                                @else
                                                    <button type="button" class="btn-danger px-2 py-1 rounded"
                                                        onclick="removeSyncUrl(this)">
                                                        <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Buttons -->
                    <div class="flex items-center justify-end space-x-2 px-4 py-2 border-t border-gray-200 ">
                        <x-primary-button type="submit" id="submitBtn" onclick="setLoading(this)"
                            class="whitespace-nowrap">
                            <span class="submit-text flex items-center gap-1">
                                <i data-lucide="save" class="h-4 w-4"></i> Update
                            </span>
                            <span class="submit-loader hidden flex items-center gap-1">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg> Updating... </span>
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
            $('.select2-basic').select2({
                allowClear: false,
                width: '100%',
                containerCssClass: 'text-xs',
                selectionCssClass: 'text-xs'
            });

            // Initialize Flatpickr for dates
            flatpickr(".datepicker", {
                dateFormat: "d/m/Y",
                allowInput: true
            });

            // Load company details (type + params)
            const initialCompany = $('#company_code').val();
            if (initialCompany) {
                fetchCompanyDetails(initialCompany, "{{ old('type', $distributor->type) }}");
            }

            // Restore chained geo — load from saved DB values
            const oldCountry = '{{ old('country', $distributor->country) }}';
            const oldRegion = '{{ old('region', $distributor->region) }}';
            const oldState = '{{ old('state', $distributor->state) }}';
            const oldCity = '{{ old('city', $distributor->city) }}';

            if (oldCountry) {
                loadRegions(oldCountry, oldRegion, function() {
                    if (oldRegion) {
                        loadStates(oldRegion, oldState, function() {
                            if (oldState) {
                                loadCities(oldState, oldCity);
                            }
                        });
                    }
                });
            }
        });

        // ─── Geo Chained Dropdowns ─────────────────────────────────────────────

        $('#country').on('change', function() {
            const countryPid = $(this).val();
            resetSelect('region', '-- Select Region --');
            resetSelect('state', '-- Select State --');
            resetSelect('city', '-- Select City --');
            if (!countryPid) return;
            loadRegions(countryPid);
        });

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

        function loadRegions(countryPid, preselectPid = null, callback = null) {
            $('#region-loader').removeClass('hidden');
            $.get(`/distributors/geo/regions/${countryPid}`, function(data) {
                let html = '<option value="">-- Select Region --</option>';
                data.forEach(function(item) {
                    const sel = (preselectPid && item.id == preselectPid) ? 'selected' : '';
                    html += `<option value="${item.id}" ${sel}>${item.name}</option>`;
                });
                $('#region').html(html);
                if (preselectPid) $('#region').val(preselectPid).trigger('change.select2');
                if (callback) callback();
            }).always(function() {
                $('#region-loader').addClass('hidden');
            });
        }

        function loadStates(regionPid, preselectPid = null, callback = null) {
            $('#state-loader').removeClass('hidden');
            $.get(`/distributors/geo/states/${regionPid}`, function(data) {
                let html = '<option value="">-- Select State --</option>';
                data.forEach(function(item) {
                    const sel = (preselectPid && item.id == preselectPid) ? 'selected' : '';
                    html += `<option value="${item.id}" ${sel}>${item.name}</option>`;
                });
                $('#state').html(html);
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
                $('#city').html(html);
                if (preselectPid) $('#city').val(preselectPid).trigger('change.select2');
            }).always(function() {
                $('#city-loader').addClass('hidden');
            });
        }

        // ─── Tally Deployed ────────────────────────────────────────────────────

        function handleTallyDeployedChange(selectElement) {
            if (selectElement.value === 'local') {
                $('#no_of_computers_container').removeClass('hidden');
                $('#existing_provider_container').addClass('hidden');
            } else {
                $('#no_of_computers_container').addClass('hidden');
                $('#existing_provider_container').removeClass('hidden');
            }
        }

        // ─── Company Details ───────────────────────────────────────────────────

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
                    let typeHtml = '<option value="">-- Select Type --</option>';
                    if (data.distributor_types && data.distributor_types.length > 0) {
                        data.distributor_types.forEach(type => {
                            const selected = (selectedType === type) ? 'selected' : '';
                            typeHtml += `<option value="${type}" ${selected}>${type}</option>`;
                        });
                    }
                    $('#type').html(typeHtml).trigger('change.select2');

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

        // ─── Contact Rows ──────────────────────────────────────────────────────

        function addContact() {
            const container = document.getElementById('contacts-container');
            const div = document.createElement('div');
            div.className = "grid grid-cols-1 md:grid-cols-[1fr_1fr_1fr_1fr_1fr_auto] gap-3 mt-2";
            div.innerHTML = `
                                        <div><input type="text" name="contact_name[]" class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600" placeholder="Contact name"></div>
                                        <div><input type="text" name="designation[]" class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600" placeholder="Designation"></div>
                                        <div><input type="email" name="email[]" class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600" placeholder="email@example.com"></div>
                                        <div><input type="text" name="mobile[]" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');" class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600" placeholder="Mobile number"></div>
                                        <div><input type="text" name="location[]" class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600" placeholder="Location"></div>
                                        <button type="button" class="btn-danger" onclick="removeContact(this)"><i data-lucide="trash" class="h-4 w-4"></i></button>
                                    `;
            container.appendChild(div);
            lucide.createIcons();
        }

        function removeContact(button) {
            button.parentElement.remove();
        }

        function setLoading(btn) {
            btn.querySelector('.submit-text').classList.add('hidden');
            btn.querySelector('.submit-loader').classList.remove('hidden');
            btn.disabled = true;
            btn.closest('form').submit();
        }

        // ─── Sync URL Rows ──────────────────────────────────────────────────────

        function addSyncUrl() {
            const container = document.getElementById('sync-urls-container');
            const div = document.createElement('div');
            div.className = "flex items-center space-x-1 sync-url-row";
            div.innerHTML = `
                                        <input type="text" name="sync_urls[]"
                                            class="flex-1 px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-600"
                                            placeholder="https://example.com/api">
                                        <button type="button" class="btn-danger px-2 py-1 rounded"
                                            onclick="removeSyncUrl(this)">
                                            <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                                        </button>
                                    `;
            container.appendChild(div);
            lucide.createIcons();
        }

        function removeSyncUrl(button) {
            button.closest('.sync-url-row').remove();
        }
    </script>
@endsection
