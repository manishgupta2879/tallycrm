@extends('layouts.app', ['breadcrumb' => 'Additional Parameters', 'breadcrumbRight' => 'Dashboard->Primary Setup->Distributor->Parameters'])

@section('content')
    <div class="max-w-full">

        <!-- Card Container -->
        <div class="bg-white rounded shadow-sm border border-gray-200">
            <div class="flex items-center justify-between py-2 px-3 border-b border-gray-200">
                <div class="flex items-center space-x-2">
                    <h6>{{ $distributor->company->name ?? 'Company' }} - {{ $distributor->name }} - Additional Parameters</h6>
                </div>
                <div class="flex items-center space-x-1">
                    <a href="{{ route('distributors.edit', $distributor->id) }}" class="btn-secondary">
                        <i data-lucide="arrow-left" class="h-4 w-4"></i> Back to Distributor
                    </a>
                </div>
            </div>

            <!-- Key-Value Header Section -->
            @if(!$parameters->isEmpty())
                <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                    <div class="grid grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Distributor</label>
                            <p class="text-sm text-gray-700">{{ $distributor->name }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Tally Serial No</label>
                            <p class="text-sm text-gray-700">{{ $parameters->first()->tallyserialno ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Principal ID</label>
                            <p class="text-sm text-gray-700">{{ $parameters->first()->principalid ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Distributor ID</label>
                            <p class="text-sm text-gray-700">{{ $parameters->first()->distributorid ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Table -->
            <div class="overflow-x-auto" style="max-height: calc(100vh - 263px);">
                <table class="w-full text-xs">
                    <thead class="sticky top-0 bg-white">
                        <tr>
                            <th>SN.</th>
                            @foreach ($parameterNames as $name)
                                <th>{{ $name }}</th>
                            @endforeach
                            <th>Last Updated</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($parameters as $sn => $param)
                            <tr class="hover:bg-[#e6e6e6] transition">
                                <td>{{ $sn + 1 }}</td>
                                @foreach ($parameterNames as $i => $name)
                                    @php
                                        $fieldName = 'p' . $i+1;
                                        $value = $param->$fieldName ?? '';
                                    @endphp
                                    <td>{{ $value ?: '' }}</td>
                                @endforeach
                                <td>{{ $param->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 1 + count($parameterNames) + 1 }}" class="px-3 text-center text-gray-500 text-xs">
                                    No parameters found for this distributor
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

