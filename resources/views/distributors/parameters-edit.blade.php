@extends('layouts.app', ['breadcrumb' => 'Additional Parameters', 'breadcrumbRight' => 'Dashboard->Primary Setup->Distributor->Parameters'])

@section('content')
    <div class="max-w-full">

        <div class="bg-white rounded shadow-sm border border-gray-200">
            <div class="flex items-center justify-between py-2 px-3 border-b border-gray-200">
                <h6>Parameter Details - {{ $distributor->name }}</h6>
                <div class="flex items-center space-x-1">
                    <a href="{{ route('distributors.parameters', $distributor->id) }}" class="btn-secondary">
                        <i data-lucide="arrow-left" class="h-4 w-4"></i> Back to Parameters List
                    </a>
                </div>
            </div>

            <div>
                <div class="overflow-x-auto px-4 relative" style="max-height: calc(100vh - 263px);">

                    @php
                        $company = $distributor->company;
                        $parameterNames = [];
                        if ($company) {
                            for ($i = 1; $i <= 10; $i++) {
                                $fieldName = 'd_parameter_' . $i;
                                if (isset($company->$fieldName) && !empty($company->$fieldName)) {
                                    $parameterNames[$i] = $company->$fieldName;
                                }
                            }
                        }
                    @endphp

                    <!-- Key Information -->
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-3 pt-2 sticky top-0 bg-white z-10 transition-shadow"
                        id="stickyHeader">
                        <div>
                            <label class="block text-gray-700 font-semibold text-xs mb-1">Distributor</label>
                            <input type="text" value="{{ $distributor->name }}" disabled
                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs bg-gray-50 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold text-xs mb-1">Tally Serial No</label>
                            <input type="text" value="{{ $parameter->tallyserialno ?? '—' }}" disabled
                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs bg-gray-50 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold text-xs mb-1">Principal ID</label>
                            <input type="text" value="{{ $parameter->principalid ?? '—' }}" disabled
                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs bg-gray-50 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold text-xs mb-1">Distributor ID</label>
                            <input type="text" value="{{ $parameter->distributorid ?? '—' }}" disabled
                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs bg-gray-50 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold text-xs mb-1">Last Updated</label>
                            <input type="text" value="{{ $parameter->updated_at->format('d/m/Y H:i') }}" disabled
                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs bg-gray-50 cursor-not-allowed">
                        </div>
                    </div>

                    <!-- Parameter Values Section -->
                    <div class="mb-3 mt-3">
                        <p
                            class="text-xs font-semibold text-gray-600 mb-2 bg-gradient-to-r from-gray-200 to-gray-100 px-2 py-1">
                            Parameter Values
                        </p>

                        @if (empty($parameterNames))
                            <div class="text-center py-8">
                                <p class="text-gray-600 text-sm">No parameters configured for this distributor</p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @for ($i = 1; $i <= 10; $i++)
                                    @php
                                        $showField = isset($parameterNames[$i]);
                                    @endphp

                                    @if ($showField)
                                        <div>
                                            <label class="block text-gray-700 font-semibold text-xs mb-1">
                                                {{ $parameterNames[$i] }}
                                            </label>
                                            <textarea disabled rows="2"
                                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs bg-gray-50 cursor-not-allowed">{{ $parameter->{'p' . $i} ?? '' }}</textarea>
                                        </div>
                                    @endif
                                @endfor
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
