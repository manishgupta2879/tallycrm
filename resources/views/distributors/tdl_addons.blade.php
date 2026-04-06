@extends('layouts.app', ['breadcrumb' => 'Distributor TDL Addons', 'breadcrumbRight' => 'Dashboard->Distributor->TDL Addons'])

@section('content')
    <div class="max-w-full">
        <div class="bg-white rounded shadow-sm border border-gray-200 h-full flex flex-col overflow-hidden">

            <!-- Header -->
            <div class="flex items-center justify-between py-2 px-3 border-b border-gray-200 flex-none">
                <h6 class="text-sm font-bold">TDL Addons History for {{ $distributor->name }} (Serial:
                    {{ $distributor->tally_serial }})</h6>
                <div class="flex items-center space-x-1">
                    <a href="{{ route('distributors.edit', $distributor->id) }}" class="btn-secondary">
                        <i data-lucide="edit-3" class="h-4 w-4 mr-1"></i> Edit
                    </a>
                    <a href="{{ route('distributors.index') }}" class="btn-secondary">
                        <i data-lucide="list" class="h-4 w-4 mr-1"></i> List
                    </a>
                </div>
            </div>

            <!-- Scrollable Table Area -->
            <div class="overflow-x-auto" style="max-height: calc(100vh - 266px);">
                <table class="w-full text-xs text-left">
                    <thead class="sticky top-0 bg-gray-50 z-10 shadow-sm">
                        <tr class="border-b border-gray-200">
                            <th>SN.</th>
                            <th>Filename</th>
                            <th>Format</th>
                            <th>File Path</th>
                            <th>Version</th>
                            <th>Expiry</th>
                            <th>Type</th>
                            <th>Author</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($addons as $sn => $addon)
                            <tr class="hover:bg-[#e6e6e6] transition">
                                <td>{{ $addons->firstItem() + $sn }}</td>
                                <td class="{{ $addon->batch_id == $latestBatchId ? 'text-black' : 'text-gray-500' }}">
                                    {{ $addon->tcp_filename }}
                                </td>
                                <td class="{{ $addon->batch_id == $latestBatchId ? 'text-black' : 'text-gray-500' }}">
                                    {{ $addon->tcp_file_format }}</td>
                                <td class="{{ $addon->batch_id == $latestBatchId ? 'text-black' : 'text-gray-500' }}">
                                    <span title="{{ $addon->tcp_filepath }}">{{ \Illuminate\Support\Str::limit($addon->tcp_filepath, 200) }}</span>
                                </td>
                                <td class="{{ $addon->batch_id == $latestBatchId ? 'text-black' : 'text-gray-500' }}">
                                    {{ $addon->tcp_version }}</td>
                                <td class="{{ $addon->batch_id == $latestBatchId ? 'text-black' : 'text-gray-500' }}">
                                    {{ $addon->tcp_expiry_date }}</td>
                                <td class="{{ $addon->batch_id == $latestBatchId ? 'text-black' : 'text-gray-500' }}">
                                    {{ $addon->tcp_source_type }}
                                </td>
                                <td class="{{ $addon->batch_id == $latestBatchId ? 'text-black' : 'text-gray-500' }}">
                                    {{ $addon->tcp_author_name }}</td>
                                <td class="{{ $addon->batch_id == $latestBatchId ? 'text-black' : 'text-gray-500' }}">
                                    {{ $addon->created_at->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-3 text-center text-gray-400 italic">
                                    No addons found for this distributor.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            <div class="bg-white border-t border-gray-200 flex-none">
                <x-pagination :currentPage="$addons->currentPage()" :totalPages="$addons->lastPage()" :totalRecords="$addons->total()" :perPage="$addons->perPage()"
                    baseUrl="{{ route('distributors.tdl-addons', $distributor->id) }}" />
            </div>
        </div>
    </div>
@endsection
