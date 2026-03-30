<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TallyLog;
use App\Models\Distributor;
use App\Models\TdlAddon;
use App\Models\CompanyFeature;

class TallyDataController extends Controller
{
    /**
     * Receive and inspect the Tally master data upload payload.
     *
     * POST /api/tally/master-data-upload
     *
     * Headers required:
     *   Content-Type: application/json
     *   X-API-Key: <your-api-key>
     */
    public function handle(Request $request)
    {
        $payload = $request->json()->all();

        if (empty($payload['masterdata_upload_request'])) {
            return response()->json(['success' => false, 'message' => 'Empty payload or missing masterdata_upload_request'], 400);
        }

        $requests = $payload['masterdata_upload_request'];
        // Collect all necessary IDs for bulk fetching to avoid N+1 queries
        $distributorCodes = [];
        $principalIds = [];
        $tallyProductSerialNos = [];
        $tallyTcpSerialNos = [];

        foreach ($requests as $item) {
            if (!empty($item['distributorinfo'])) {
                foreach ($item['distributorinfo'] as $dist) {
                    if (!empty($dist['distributorid'])) {
                        $distributorCodes[] = $dist['distributorid'];
                    }
                    if (!empty($dist['principalid'])) {
                        $principalIds[] = $dist['principalid'];
                    }
                }
            }
            if (!empty($item['tallyproductinfo']['tallyserialno'])) {
                $tallyProductSerialNos[] = $item['tallyproductinfo']['tallyserialno'];
            }

            if (!empty($item['tdladdonsinfo'])) {
                foreach ($item['tdladdonsinfo'] as $tdl) {
                    if (!empty($tdl['tallyserialno'])) {
                        $tallyTcpSerialNos[] = $tdl['tallyserialno'];
                    }
                }
            }
        }

        $existingCompanies = \App\Models\Company::whereIn('pid', array_unique($principalIds ?? []))
            ->pluck('pid')->flip();

        // Fetch existing distributors and key them by a composite triplet (company_code | code | tally_serial)
        $existingDistributors = Distributor::whereIn('code', array_unique($distributorCodes))
            ->get()->keyBy(fn($d) => "{$d->company_code}|{$d->code}|{$d->tally_serial}");

        $latestLogs = TallyLog::whereIn('tally_serial_no', array_unique($tallyProductSerialNos))
            ->orderBy('id', 'desc')->get()->unique('tally_serial_no')->keyBy('tally_serial_no');

        // Main processing loop
        foreach ($requests as $item) {
            // 1. Process Distributor Information (Add only if not exists)
            if (!empty($item['distributorinfo'])) {
                foreach ($item['distributorinfo'] as $distData) {
                    $distCode = $distData['distributorid'] ?? null;
                    $principalId = $distData['principalid'] ?? null;
                    $principalName = $distData['principalcompany'] ?? null;
                    $tallySerial = $distData['tallyserialno'] ?? null;

                    if (!$distCode)
                        continue;

                    // Check if principal exists
                    $finalCompanyCode = $principalId;
                    $params = [];

                    if ($principalId && !$existingCompanies->has($principalId)) {
                        $finalCompanyCode = 'UNCATEGORIZED';
                        $params['master_principal_id'] = $principalId;
                        $params['master_principal_name'] = $principalName;
                    }

                    // Unique check based on the 3-field combination
                    $compositeKey = "{$finalCompanyCode}|{$distCode}|{$tallySerial}";

                    if (!$existingDistributors->has($compositeKey)) {
                        // Create new distributor as it doesn't exist
                        $distributor = Distributor::create([
                            'code' => $distCode,
                            'company_code' => $finalCompanyCode,
                            'name' => $distData['distname'] ?? 'N/A',
                            'address' => $distData['distaddress'] ?? null,
                            'tally_serial' => $tallySerial,
                            'pan_number' => $distData['distpanno'] ?? null,
                            'gst_number' => $distData['distgstno'] ?? null,
                            'status' => $distData['diststatus'] ?? 'Active',
                            'rollout_done_date' => !empty($distData['rolloutdate']) ? $distData['rolloutdate'] : null,
                            'dist_perm_pass' => $distData['distpermpass'] ?? null,
                            'last_sync_date' => $distData['lastsyncdate'] ?? null,
                            'no_of_sync_urls' => $distData['noofsyncurls'] ?? null,
                            'c_urls' => !empty($distData['syncurls']) 
                                ? (is_array($distData['syncurls'][0]) 
                                    ? array_column($distData['syncurls'], 'syncurl') 
                                    : $distData['syncurls']) 
                                : null,
                            'params' => !empty($params) ? $params : null,
                        ]);

                        // Add primary contact info into contacts morph relation
                        if (!empty($distData['distcontactperson']) || !empty($distData['distemailid']) || !empty($distData['distmobileno'])) {
                            $distributor->contacts()->create([
                                'name' => $distData['distcontactperson'] ?: null,
                                'email' => $distData['distemailid'] ?: null,
                                'mobile' => $distData['distmobileno'] ?: null
                            ]);
                            // Cache locally for the rest of this request
                            $existingDistributors->put($compositeKey, $distributor);
                        }
                    }
                }
            }

            // 2. Process Tally Product Logs (Insert if anything has changed)
            if (!empty($item['tallyproductinfo'])) {
                $prod = $item['tallyproductinfo'];
                $serialNo = $prod['tallyserialno'] ?? null;

                if ($serialNo) {
                    $latestLog = $latestLogs->get($serialNo);

                    // Detect changes in required Tally product fields
                    $hasChanged = !$latestLog ||
                        $latestLog->tally_version !== ($prod['tallyversion'] ?? '') ||
                        $latestLog->tally_release !== ($prod['tallyrelease'] ?? '') ||
                        $latestLog->tally_edition !== ($prod['tallyedition'] ?? '') ||
                        $latestLog->account_id !== ($prod['accountid'] ?? '') ||
                        $latestLog->tss_expiry_date !== ($prod['tssexpirydate'] ?? '');

                    if ($hasChanged) {
                        // Update existing distributors first
                        Distributor::where('tally_serial', $serialNo)->update([
                            'tally_version' => $prod['tallyversion'] ?? null,
                            'tally_release' => $prod['tallyrelease'] ?? null,
                            'tally_edition' => $prod['tallyedition'] ?? null,
                            'tally_expiry' => $prod['tssexpirydate'] ?? null,
                            'tally_net_id' => $prod['accountid'] ?? null,
                        ]);

                        // Insert new request log entry due to variation in data
                        TallyLog::create([
                            'tally_serial_no' => $serialNo,
                            'tally_version' => $prod['tallyversion'] ?? '',
                            'tally_release' => $prod['tallyrelease'] ?? '',
                            'tally_edition' => $prod['tallyedition'] ?? '',
                            'account_id' => $prod['accountid'] ?? '',
                            'tss_expiry_date' => $prod['tssexpirydate'] ?? '',
                        ]);

                        // Update our latest cache for the remainder of this request
                        $latestLogs->put($serialNo, (object)[
                            'tally_version' => $prod['tallyversion'] ?? '',
                            'tally_release' => $prod['tallyrelease'] ?? '',
                            'tally_edition' => $prod['tallyedition'] ?? '',
                            'account_id' => $prod['accountid'] ?? '',
                            'tss_expiry_date' => $prod['tssexpirydate'] ?? ''
                        ]);
                    }
                }
            }

            // 3. Process TDL Addons (Grouped update: if any change is detected, all addons are re-inserted as a new batch)
            if (!empty($item['tdladdonsinfo'])) {
                $addonRequests = $item['tdladdonsinfo'];
                $serialNo = $addonRequests[0]['tallyserialno'] ?? null;

                if ($serialNo) {
                    $latestBatchId = TdlAddon::where('tally_serial_no', '=', $serialNo, 'and')->max('batch_id') ?? 0;
                    $currentAddons = TdlAddon::where('tally_serial_no', '=', $serialNo, 'and')
                        ->where('batch_id', '=', $latestBatchId, 'and')
                        ->get()
                        ->keyBy('tcp_filename');

                    $hasChanged = (count($addonRequests) !== $currentAddons->count());

                    if (!$hasChanged) {
                        foreach ($addonRequests as $newAddon) {
                            $fname = $newAddon['tcpfilename'] ?? $newAddon['tcp_filename'] ?? null;
                            $existing = $currentAddons->get($fname);
                            if (!$existing) { 
                                $hasChanged = true; break; 
                            }

                            $changed = $existing->tcp_file_format !== ($newAddon['tcp_file_format'] ?? $newAddon['tcpfileformat'] ?? '') ||
                                $existing->tcp_version !== ($newAddon['tcp_version'] ?? $newAddon['tcpversion'] ?? '') ||
                                $existing->tcp_expiry_date !== ($newAddon['tcp_expiry_date'] ?? $newAddon['tcpexpirydate'] ?? '') ||
                                $existing->tcp_source_type !== ($newAddon['tcp_source_type'] ?? $newAddon['tcpsourcetype'] ?? '') ||
                                $existing->tcp_author_name !== ($newAddon['tcp_author_name'] ?? $newAddon['tcpauthorname'] ?? '') ||
                                $existing->tcp_author_email_id !== ($newAddon['tcp_author_email_id'] ?? $newAddon['tcpauthoremailid'] ?? '') ||
                                $existing->tcp_author_website !== ($newAddon['tcp_author_website'] ?? $newAddon['tcpauthorwebsite'] ?? '');

                            if ($changed) { 
                                $hasChanged = true; break; 
                            }
                        }
                    }

                    if ($hasChanged || $latestBatchId === 0) {
                        $newBatchId = $latestBatchId + 1;
                        foreach ($addonRequests as $addonData) {
                            TdlAddon::create([
                                'batch_id' => $newBatchId,
                                'tally_serial_no' => $serialNo,
                                'tcp_filename' => $addonData['tcpfilename'] ?? $addonData['tcp_filename'] ?? '',
                                'tcp_file_format' => $addonData['tcp_file_format'] ?? $addonData['tcpfileformat'] ?? '',
                                'tcp_version' => $addonData['tcp_version'] ?? $addonData['tcpversion'] ?? '',
                                'tcp_expiry_date' => $addonData['tcp_expiry_date'] ?? $addonData['tcpexpirydate'] ?? '',
                                'tcp_source_type' => $addonData['tcp_source_type'] ?? $addonData['tcpsourcetype'] ?? '',
                                'tcp_author_name' => $addonData['tcp_author_name'] ?? $addonData['tcpauthorname'] ?? '',
                                'tcp_author_email_id' => $addonData['tcp_author_email_id'] ?? $addonData['tcpauthoremailid'] ?? '',
                                'tcp_author_website' => $addonData['tcp_author_website'] ?? $addonData['tcpauthorwebsite'] ?? '',
                            ]);
                        }
                    }
                }
            }

            // 4. Process Company Features (Insert if anything has changed)
            if (!empty($item['companyfeaturesinfo'])) {
                foreach ($item['companyfeaturesinfo'] as $featureData) {
                    $serialNo = $featureData['tallyserialno'] ?? null;
                    $distName = $featureData['distname'] ?? null;

                    if ($serialNo && $distName) {
                        $latestFeature = CompanyFeature::where('tally_serial_no', '=', $serialNo, 'and')
                            ->where('dist_name', '=', $distName, 'and')
                            ->orderBy('id', 'desc')
                            ->first();

                        $hasChanged = !$latestFeature ||
                            $latestFeature->state_name !== ($featureData['StateName'] ?? '') ||
                            $latestFeature->country_name !== ($featureData['CountryName'] ?? '') ||
                            $latestFeature->mobile_numbers !== ($featureData['MobileNumbers'] ?? '') ||
                            $latestFeature->corporate_identity_no !== ($featureData['CorporateIdentityNo'] ?? '') ||
                            $latestFeature->income_tax_number !== ($featureData['IncomeTaxNumber'] ?? '') ||
                            $latestFeature->is_tcs_on !== ($featureData['IsTcsOn'] ?? '') ||
                            $latestFeature->is_tds_on !== ($featureData['IsTDSOn'] ?? '') ||
                            $latestFeature->tan_number !== ($featureData['TANumber'] ?? '') ||
                            $latestFeature->tds_deductor_type !== ($featureData['TDSDeductorType'] ?? '') ||
                            $latestFeature->person_responsible_flat_no !== ($featureData['PersonResponsibleFlatNo'] ?? '') ||
                            $latestFeature->person_responsible_premises !== ($featureData['PersonResponsiblePremises'] ?? '') ||
                            $latestFeature->person_responsible_mobile !== ($featureData['PersonResponsibleMobile'] ?? '') ||
                            $latestFeature->person_responsible_phone !== ($featureData['PersonResponsiblePhone'] ?? '') ||
                            $latestFeature->person_responsible_email !== ($featureData['PersonResponsibleEMail'] ?? '') ||
                            $latestFeature->person_responsible_state !== ($featureData['PersonResponsibleState'] ?? '') ||
                            $latestFeature->is_gst_on !== ($featureData['IsGSTOn'] ?? '') ||
                            $latestFeature->gst_no !== ($featureData['GSTNo'] ?? '') ||
                            $latestFeature->gst_user_name !== ($featureData['GSTUserName'] ?? '') ||
                            $latestFeature->gst_signing_mode !== ($featureData['GSTSigningMode'] ?? '') ||
                            $latestFeature->is_e_invoice_applicable !== ($featureData['IseInvoiceApplicable'] ?? '') ||
                            $latestFeature->e_invoice_applicable_date !== ($featureData['eInvoiceApplicableDate'] ?? '') ||
                            $latestFeature->e_invoice_bill_from_place !== ($featureData['eInvoiceBillFromPlace'] ?? '') ||
                            $latestFeature->is_e_way_bill_applicable !== ($featureData['IseWayBillApplicable'] ?? '') ||
                            $latestFeature->e_way_bill_applicable_date !== ($featureData['eWayBillApplicableDate'] ?? '') ||
                            $latestFeature->e_way_bill_has_interstate !== ($featureData['eWayBillHasInterstate'] ?? '') ||
                            $latestFeature->e_way_bill_address_type !== ($featureData['eWayBillAddressType'] ?? '') ||
                            $latestFeature->e_way_bill_applicable_type !== ($featureData['eWayBillApplicableType'] ?? '') ||
                            $latestFeature->e_way_bill_inter_state_threshold !== ($featureData['eWayBillInterStateThreshold'] ?? '') ||
                            $latestFeature->msme_enterprise_type !== ($featureData['MSMEEnterpriseType'] ?? '') ||
                            $latestFeature->msme_udyam_reg_no !== ($featureData['MSMEUdyamRegNo'] ?? '') ||
                            $latestFeature->msme_activity_type !== ($featureData['MSMEActivityType'] ?? '') ||
                            $latestFeature->is_accounting_on !== ($featureData['IsAccountingOn'] ?? '') ||
                            $latestFeature->is_inventory_on !== ($featureData['IsInventoryOn'] ?? '') ||
                            $latestFeature->is_integrated !== ($featureData['IsIntegrated'] ?? '') ||
                            $latestFeature->is_bill_wise_on !== ($featureData['IsBillWiseOn'] ?? '') ||
                            $latestFeature->is_cost_centres_on !== ($featureData['IsCostCentresOn'] ?? '') ||
                            $latestFeature->is_interest_on !== ($featureData['IsInterestOn'] ?? '') ||
                            $latestFeature->is_batch_wise_on !== ($featureData['IsBatchWiseOn'] ?? '') ||
                            $latestFeature->is_perishable_on !== ($featureData['IsPerishableOn'] ?? '') ||
                            $latestFeature->is_chq_printing_on !== ($featureData['IsChqPrintingOn'] ?? '') ||
                            $latestFeature->use_zero_entries !== ($featureData['UseZeroEntries'] ?? '') ||
                            $latestFeature->is_payroll_on !== ($featureData['IsPayrollOn'] ?? '') ||
                            $latestFeature->is_discounts_on !== ($featureData['IsDiscountsOn'] ?? '') ||
                            $latestFeature->use_price_levels !== ($featureData['UsePriceLevels'] ?? '') ||
                            $latestFeature->is_payment_request_on !== ($featureData['IsPaymentRequestOn'] ?? '') ||
                            $latestFeature->is_multi_address_on !== ($featureData['IsMultiAddressOn'] ?? '') ||
                            $latestFeature->is_job_work_on !== ($featureData['IsJobWorkOn'] ?? '');

                        if ($hasChanged) {
                            CompanyFeature::create([
                                'tally_serial_no' => $serialNo,
                                'dist_name' => $distName,
                                'state_name' => $featureData['StateName'] ?? '',
                                'country_name' => $featureData['CountryName'] ?? '',
                                'mobile_numbers' => $featureData['MobileNumbers'] ?? '',
                                'corporate_identity_no' => $featureData['CorporateIdentityNo'] ?? '',
                                'income_tax_number' => $featureData['IncomeTaxNumber'] ?? '',
                                'is_tcs_on' => $featureData['IsTcsOn'] ?? '',
                                'is_tds_on' => $featureData['IsTDSOn'] ?? '',
                                'tan_number' => $featureData['TANumber'] ?? '',
                                'tds_deductor_type' => $featureData['TDSDeductorType'] ?? '',
                                'person_responsible_flat_no' => $featureData['PersonResponsibleFlatNo'] ?? '',
                                'person_responsible_premises' => $featureData['PersonResponsiblePremises'] ?? '',
                                'person_responsible_mobile' => $featureData['PersonResponsibleMobile'] ?? '',
                                'person_responsible_phone' => $featureData['PersonResponsiblePhone'] ?? '',
                                'person_responsible_email' => $featureData['PersonResponsibleEMail'] ?? '',
                                'person_responsible_state' => $featureData['PersonResponsibleState'] ?? '',
                                'is_gst_on' => $featureData['IsGSTOn'] ?? '',
                                'gst_no' => $featureData['GSTNo'] ?? '',
                                'gst_user_name' => $featureData['GSTUserName'] ?? '',
                                'gst_signing_mode' => $featureData['GSTSigningMode'] ?? '',
                                'is_e_invoice_applicable' => $featureData['IseInvoiceApplicable'] ?? '',
                                'e_invoice_applicable_date' => $featureData['eInvoiceApplicableDate'] ?? '',
                                'e_invoice_bill_from_place' => $featureData['eInvoiceBillFromPlace'] ?? '',
                                'is_e_way_bill_applicable' => $featureData['IseWayBillApplicable'] ?? '',
                                'e_way_bill_applicable_date' => $featureData['eWayBillApplicableDate'] ?? '',
                                'e_way_bill_has_interstate' => $featureData['eWayBillHasInterstate'] ?? '',
                                'e_way_bill_address_type' => $featureData['eWayBillAddressType'] ?? '',
                                'e_way_bill_applicable_type' => $featureData['eWayBillApplicableType'] ?? '',
                                'e_way_bill_inter_state_threshold' => $featureData['eWayBillInterStateThreshold'] ?? '',
                                'msme_enterprise_type' => $featureData['MSMEEnterpriseType'] ?? '',
                                'msme_udyam_reg_no' => $featureData['MSMEUdyamRegNo'] ?? '',
                                'msme_activity_type' => $featureData['MSMEActivityType'] ?? '',
                                'is_accounting_on' => $featureData['IsAccountingOn'] ?? '',
                                'is_inventory_on' => $featureData['IsInventoryOn'] ?? '',
                                'is_integrated' => $featureData['IsIntegrated'] ?? '',
                                'is_bill_wise_on' => $featureData['IsBillWiseOn'] ?? '',
                                'is_cost_centres_on' => $featureData['IsCostCentresOn'] ?? '',
                                'is_interest_on' => $featureData['IsInterestOn'] ?? '',
                                'is_batch_wise_on' => $featureData['IsBatchWiseOn'] ?? '',
                                'is_perishable_on' => $featureData['IsPerishableOn'] ?? '',
                                'is_chq_printing_on' => $featureData['IsChqPrintingOn'] ?? '',
                                'use_zero_entries' => $featureData['UseZeroEntries'] ?? '',
                                'is_payroll_on' => $featureData['IsPayrollOn'] ?? '',
                                'is_discounts_on' => $featureData['IsDiscountsOn'] ?? '',
                                'use_price_levels' => $featureData['UsePriceLevels'] ?? '',
                                'is_payment_request_on' => $featureData['IsPaymentRequestOn'] ?? '',
                                'is_multi_address_on' => $featureData['IsMultiAddressOn'] ?? '',
                                'is_job_work_on' => $featureData['IsJobWorkOn'] ?? '',
                            ]);
                        }
                    }
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Processed successfully']);
    }
}
