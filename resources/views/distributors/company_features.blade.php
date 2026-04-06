@extends('layouts.app', ['breadcrumb' => 'Distributor Company Features', 'breadcrumbRight' => 'Dashboard->Distributor->Company Features'])

@section('content')
    <div class="max-w-full">

        <!-- Main Application Card -->
        <div class="bg-white rounded shadow-sm border border-gray-200 h-full flex flex-col overflow-hidden">

            <!-- Header -->
            <div class="bg-gray-50 px-3 py-2 border-b border-gray-200 flex justify-between items-center bg-white flex-none">
                <span class="text-xs font-bold text-gray-800 uppercase tracking-widest">Company Features:
                    {{ $distributor->name }} ({{ $distributor->tally_serial }})</span>
                <div class="flex space-x-1">
                    <a href="{{ route('distributors.edit', $distributor->id) }}"
                        class="btn-secondary px-3 py-1.5 text-xs font-bold border border-gray-300 rounded flex items-center">
                        <i data-lucide="edit-3" class="h-4 w-4 mr-1"></i> Edit
                    </a>
                    <a href="{{ route('distributors.index') }}"
                        class="btn-secondary px-3 py-1.5 text-xs font-bold border border-gray-300 rounded flex items-center">
                        <i data-lucide="list" class="h-4 w-4 mr-1"></i> List
                    </a>
                </div>
            </div>

            <!-- Scrollable Body -->
            <div class="overflow-x-auto" style="max-height: calc(100vh - 220px);">
                <div class="flex-1 overflow-y-auto bg-gray-50/20 p-4 space-y-6">
                    @forelse ($features as $f)
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-4 text-xs leading-relaxed">

                            <!-- Column 1: Identity, Location & Responsible Person -->
                            <div class="space-y-1.5">
                                <div class="flex justify-between border-b border-gray-50 pb-0.5"><span
                                        class="text-gray-500 font-medium whitespace-nowrap">Corp ID:</span> <span
                                        class="text-black font-semibold break-all ml-2 text-right">{{ $f->corporate_identity_no ?: '—' }}</span>
                                </div>
                                <div class="flex justify-between border-b border-gray-50 pb-0.5"><span
                                        class="text-gray-500 font-medium">PAN No:</span> <span
                                        class="text-black font-semibold">{{ $f->income_tax_number ?: '—' }}</span></div>
                                <div class="flex justify-between border-b border-gray-50 pb-0.5"><span
                                        class="text-gray-500 font-medium">MSME Type:</span> <span
                                        class="text-black font-semibold">{{ $f->msme_enterprise_type ?: '—' }}</span>
                                </div>
                                <div class="flex justify-between border-b border-gray-50 pb-0.5"><span
                                        class="text-gray-500 font-medium">MSME Reg:</span> <span
                                        class="text-black font-semibold">{{ $f->msme_udyam_reg_no ?: '—' }}</span>
                                </div>
                                <div class="flex justify-between border-b border-gray-50 pb-0.5"><span
                                        class="text-gray-500 font-medium">MSME Act:</span> <span
                                        class="text-black font-semibold">{{ $f->msme_activity_type ?: '—' }}</span>
                                </div>

                                <div class="">
                                    <div class="flex flex-col border-b border-gray-50 pb-0.5 mb-1">
                                        <span class="text-gray-400 font-medium text-[9px] uppercase">Resp. Person
                                            Address:</span>
                                        <span
                                            class="font-bold text-black leading-tight">{{ $f->person_responsible_premises }}</span>
                                    </div>
                                    <div class="flex justify-between border-b border-gray-50 pb-0.5"><span
                                            class="text-gray-500 font-medium">Resp. Mobile:</span> <span
                                            class="text-black font-semibold">{{ $f->person_responsible_mobile ?: '—' }}</span>
                                    </div>
                                    <div class="flex justify-between"><span class="text-gray-500 font-medium truncate">Resp.
                                            Email:</span> <span class="text-black font-semibold truncate ml-2"
                                            title="{{ $f->person_responsible_email }}">{{ $f->person_responsible_email ?: '—' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Column 2: Statutory, Tax & Operations -->
                            <div class="space-y-1.5 lg:border-l border-gray-100 lg:pl-4">
                                <div class="flex justify-between border-b border-gray-50 pb-0.5"><span
                                        class="text-gray-500 font-medium">GST On:</span> <span
                                        class="text-black font-semibold">{{ $f->is_gst_on }}</span></div>
                                <div class="flex justify-between border-b border-gray-50 pb-0.5"><span
                                        class="text-gray-500 font-medium">GST No:</span> <span
                                        class="text-black font-semibold uppercase">{{ $f->gst_no ?: '—' }}</span></div>
                                <div class="flex justify-between border-b border-gray-50 pb-0.5"><span
                                        class="text-gray-500 font-medium">GST User:</span> <span
                                        class="text-black font-semibold">{{ $f->gst_user_name ?: '—' }}</span></div>
                                <div class="flex justify-between border-b border-gray-50 pb-0.5"><span
                                        class="text-gray-500 font-medium">TCS On:</span> <span
                                        class="text-black font-semibold">{{ $f->is_tcs_on }}</span></div>
                                <div class="flex justify-between border-b border-gray-50 pb-0.5"><span
                                        class="text-gray-500 font-medium">TDS On:</span> <span
                                        class="text-black font-semibold">{{ $f->is_tds_on }}</span></div>
                                <div class="flex justify-between border-b border-gray-50 pb-0.5"><span
                                        class="text-gray-500 font-medium">TAN No:</span> <span
                                        class="text-black font-semibold uppercase">{{ $f->tan_number ?: '—' }}</span>
                                </div>
                                <div class="flex justify-between border-b border-gray-50 pb-0.5"><span
                                        class="text-gray-500 font-medium">Deductor:</span> <span
                                        class="text-black font-semibold">{{ $f->tds_deductor_type ?: '—' }}</span>
                                </div>
                                <div class="flex justify-between border-b border-gray-50 pb-0.5"><span
                                        class="text-gray-500 font-medium">Signing:</span> <span
                                        class="text-black font-semibold">{{ $f->gst_signing_mode ?: '—' }}</span></div>

                                <div class="">
                                    <div class="flex justify-between border-b border-gray-50 pb-0.5"><span
                                            class="text-gray-500 font-medium">Job Work:</span> <span
                                            class="text-black font-semibold">{{ $f->is_job_work_on }}</span></div>
                                    <div class="flex justify-between"><span class="text-gray-500 font-medium">Zero
                                            Entry:</span> <span
                                            class="text-black font-semibold">{{ $f->use_zero_entries }}</span></div>
                                </div>
                            </div>

                            <!-- Column 3: Features & Financials -->
                            <div class="space-y-1.5 lg:border-l border-gray-100 lg:pl-4">
                                <div class="flex justify-between border-b border-gray-50 pb-0.5"><span
                                        class="text-gray-500 font-medium">e-Invoice:</span> <span
                                        class="text-black font-semibold">{{ $f->is_e_invoice_applicable }}</span></div>
                                <div class="flex justify-between border-b border-gray-50 pb-0.5"><span
                                        class="text-gray-500 font-medium">e-Way Bill:</span> <span
                                        class="text-black font-semibold">{{ $f->is_e_way_bill_applicable }}</span>
                                </div>
                                <div class="flex justify-between border-b border-gray-50 pb-0.5"><span
                                        class="text-gray-500 font-medium">Inventory:</span> <span
                                        class="text-black font-semibold">{{ $f->is_inventory_on }}</span></div>
                                <div class="flex justify-between border-b border-gray-50 pb-0.5"><span
                                        class="text-gray-500 font-medium">Accounting:</span> <span
                                        class="text-black font-semibold">{{ $f->is_accounting_on }}</span></div>
                                <div class="flex justify-between border-b border-gray-50 pb-0.5"><span
                                        class="text-gray-500 font-medium">Integrated:</span> <span
                                        class="text-black font-semibold">{{ $f->is_integrated }}</span></div>
                                <div class="flex justify-between border-b border-gray-50 pb-0.5"><span
                                        class="text-gray-500 font-medium">Bill Wise:</span> <span
                                        class="text-black font-semibold">{{ $f->is_bill_wise_on }}</span></div>
                                <div class="flex justify-between border-b border-gray-50 pb-0.5"><span
                                        class="text-gray-500 font-medium">Payroll:</span> <span
                                        class="text-black font-semibold">{{ $f->is_payroll_on }}</span></div>
                                <div class="flex justify-between border-b border-gray-50 pb-0.5"><span
                                        class="text-gray-500 font-medium">Multi Addr:</span> <span
                                        class="text-black font-semibold">{{ $f->is_multi_address_on }}</span></div>

                                <div class="">
                                    <div class="flex justify-between border-b border-gray-50 pb-0.5"><span
                                            class="text-gray-500 font-medium">Price Level:</span> <span
                                            class="text-black font-semibold">{{ $f->use_price_levels }}</span></div>
                                    <div class="flex justify-between"><span
                                            class="text-gray-500 font-medium">Payment Req:</span> <span
                                            class="text-black font-semibold">{{ $f->is_payment_request_on }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Column 4: Tally Plugins -->
                            <div class="space-y-1.5 lg:border-l border-gray-100 lg:pl-4">
                                <div class="flex justify-between border-b border-gray-50 pb-0.5">
                                    <span class="text-gray-500 font-bold uppercase text-[9px]">Tally Plugins:</span>
                                    <span class="text-primary-800 font-bold">{{ $f->nooftallyplugins ?: 0 }}</span>
                                </div>
                                <div class="mt-2 space-y-1 max-h-40 overflow-y-auto pr-1">
                                    @if ($f->tallyplugins && is_array($f->tallyplugins))
                                        @foreach ($f->tallyplugins as $plugin)
                                            <div class="bg-blue-50/50 p-1.5 rounded border border-blue-100/50">
                                                <div class="font-semibold text-blue-900 break-words">
                                                    {{ $plugin['pluginname'] ?? 'Unknown' }}</div>
                                                <div class="flex justify-between text-[10px] text-blue-700 mt-0.5">
                                                    <span>{{ $plugin['pluginversion'] ?? '' }}</span>
                                                    <span>{{ $plugin['pluginstatus'] ?? '' }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="text-gray-400 italic text-[10px]">No plugins listed.</div>
                                    @endif
                                </div>
                                <div class="pt-2 border-t border-gray-50">
                                    <div class="flex justify-between text-[10px]">
                                        <span class="text-gray-400">Sync:</span>
                                        <span
                                            class="text-gray-500 font-medium">{{ $f->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @empty
                        <div class="p-12 text-center text-gray-400 font-medium italic border-2 border-dashed rounded-lg">
                            No features found for this distributor.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
