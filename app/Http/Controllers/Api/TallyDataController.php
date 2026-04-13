<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TallyLog;
use App\Models\Distributor;
use App\Models\TdlAddon;
use App\Models\CompanyFeature;
use App\Models\TurnoverInfo;
use App\Models\DistributorParameter;

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

        // Clean the payload: convert all string "null" values to actual null
        $payload = $this->cleanNullStrings($payload);

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
            ->get()->keyBy(function ($d) {
                return "{$d->company_code}|{$d->code}|{$d->tally_serial}";
            });

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

                    if (empty($principalId) || !$existingCompanies->has($principalId)) {
                        $finalCompanyCode = 'MISC';

                        $params['master_principal_id'] = $principalId;
                        if ($principalName) {
                            $params['master_principal_name'] = $principalName;
                        }
                    }

                    // Unique check based on the 3-field combination
                    $compositeKey = "{$finalCompanyCode}|{$distCode}|{$tallySerial}";

                    $distDataToSave = [
                        'name' => $distData['distname'] ?? 'N/A',
                        'country' => $distData['CountryName'] ?? null,
                        'state' => $distData['StateName'] ?? null,
                        'address' => $distData['distaddress'] ?? null,
                        'pan_number' => $distData['distpanno'] ?? null,
                        'gst_number' => $distData['distgstno'] ?? null,
                        'status' => $distData['diststatus'] ?? 'Active',
                        'rollout_done_date' => $this->formatDate($distData['rolloutdate'] ?? null),
                        'dist_perm_pass' => $distData['distpermpass'] ?? null,
                        'last_sync_date' => $this->formatDate($distData['lastsyncdate'] ?? null),
                        'no_of_sync_urls' => $distData['noofsyncurls'] ?? null,
                        'msme_no' => $distData['distmsmeno'] ?? null,
                        'tan_no' => $distData['disttanno'] ?? null,
                        'c_urls' => !empty($distData['syncurls'])
                            ? (is_array($distData['syncurls'][0])
                                ? array_column($distData['syncurls'], 'syncurl')
                                : $distData['syncurls'])
                            : null,
                        'params' => !empty($params) ? $params : null,
                        // New fields
                        'userid' => $distData['userid'] ?? null,
                        'authcode' => $distData['authcode'] ?? null,
                        'authcode2' => $distData['authcode2'] ?? null,
                    ];

                    $distributor = Distributor::updateOrCreate(
                        [
                            'code' => $distCode,
                            'company_code' => $finalCompanyCode,
                            'tally_serial' => $tallySerial,
                        ],
                        $distDataToSave
                    );

                    // Add or update primary contact info into contacts morph relation
                    if (!empty($distData['distcontactperson']) || !empty($distData['distemailid']) || !empty($distData['distmobileno'])) {
                        $contactData = [
                            'name' => $distData['distcontactperson'] ?: null,
                            'email' => $distData['distemailid'] ?: null,
                            'mobile' => $distData['distmobileno'] ?: null,
                            'faxnumber' => $distData['faxnumber'] ?? null,
                            'website' => $distData['website'] ?? null,
                        ];

                        $contact = $distributor->contacts()->first();
                        if ($contact) {
                            $contact->update($contactData);
                        } else {
                            $distributor->contacts()->create($contactData);
                        }
                    }

                    // Handle distributor parameters sync (static columns p1-p10)
                    if (isset($distData['addlparameters']) && is_array($distData['addlparameters'])) {
                        $this->syncDistributorParameters(
                            $distData['tallyserialno'] ?? null,
                            $distData['principalid'] ?? null,
                            $distData['distributorid'] ?? null,
                            $distData['distname'] ?? null,
                            $distData['addlparameters']
                        );
                    }

                    // Cache locally for the rest of this request
                    $existingDistributors->put($compositeKey, $distributor);
                }
            }

            // 2. Process Tally Product Logs (Insert if anything has changed)
            if (!empty($item['tallyproductinfo'])) {
                $prod = $item['tallyproductinfo'];
                $serialNo = $prod['tallyserialno'] ?? null;

                if ($serialNo) {
                    $latestLog = $latestLogs->get($serialNo);

                    $tssExpiryFormatted = $this->formatDate($prod['tssexpirydate'] ?? null);

                    // Detect changes in required Tally product fields
                    $hasChanged = !$latestLog ||
                        $latestLog->tally_version !== ($prod['tallyversion'] ?? '') ||
                        $latestLog->tally_release !== ($prod['tallyrelease'] ?? '') ||
                        $latestLog->tally_edition !== ($prod['tallyedition'] ?? '') ||
                        $latestLog->account_id !== ($prod['accountid'] ?? '') ||
                        $latestLog->tss_expiry_date !== ($tssExpiryFormatted ?? '');

                    // Update existing distributors first
                    Distributor::where('tally_serial', $serialNo)->update([
                        'tally_version' => $prod['tallyversion'] ?? null,
                        'tally_release' => $prod['tallyrelease'] ?? null,
                        'tally_edition' => $prod['tallyedition'] ?? null,
                        'tally_expiry' => $tssExpiryFormatted,
                        'tally_net_id' => $prod['accountid'] ?? null,
                    ]);

                    if ($hasChanged) {


                        // Insert new request log entry due to variation in data
                        TallyLog::create([
                            'tally_serial_no' => $serialNo,
                            'tally_version' => $prod['tallyversion'] ?? '',
                            'tally_release' => $prod['tallyrelease'] ?? '',
                            'tally_edition' => $prod['tallyedition'] ?? '',
                            'account_id' => $prod['accountid'] ?? '',
                            'tss_expiry_date' => $tssExpiryFormatted ?? '',
                        ]);

                        // Update our latest cache for the remainder of this request
                        $latestLogs->put($serialNo, (object) [
                            'tally_version' => $prod['tallyversion'] ?? '',
                            'tally_release' => $prod['tallyrelease'] ?? '',
                            'tally_edition' => $prod['tallyedition'] ?? '',
                            'account_id' => $prod['accountid'] ?? '',
                            'tss_expiry_date' => $tssExpiryFormatted ?? ''
                        ]);
                    }
                }
            }

            // 3. Process TDL Addons (Create only if combination of tallyserialno, tcpfilename, tcpfilepath doesn't exist)
            if (!empty($item['tdladdonsinfo'])) {
                $addonRequests = $item['tdladdonsinfo'];
                $serialNo = $addonRequests[0]['tallyserialno'] ?? null;

                if ($serialNo) {
                    foreach ($addonRequests as $addonData) {
                        $tcpFilename = $addonData['tcpfilename'] ?? $addonData['tcp_filename'] ?? '';
                        $tcpFilePath = $addonData['tcpfilepath'] ?? $addonData['tcp_filepath'] ?? '';

                        // Check if this combination already exists
                        $exists = TdlAddon::where('tally_serial_no', $serialNo)
                            ->where('tcp_filename', $tcpFilename)
                            ->where('tcp_filepath', $tcpFilePath)
                            ->exists();

                        // Create only if the combination doesn't exist
                        if (!$exists) {
                            TdlAddon::create([
                                'tally_serial_no' => $serialNo,
                                'tcp_filename' => $tcpFilename,
                                'tcp_filepath' => $tcpFilePath,
                                'tcp_file_format' => $addonData['tcp_file_format'] ?? $addonData['tcpfileformat'] ?? '',
                                'tcp_version' => $addonData['tcp_version'] ?? $addonData['tcpversion'] ?? '',
                                'tcp_expiry_date' => $this->formatDate($addonData['tcpexpirydate'] ?? $addonData['tcp_expiry_date'] ?? null) ?? '',
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
                        CompanyFeature::updateOrCreate(
                            [
                                'tally_serial_no' => $serialNo,
                                'dist_name' => $distName,
                            ],
                            [
                                'corporate_identity_no' => $featureData['CorporateIdentityNo'],
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
                                'nooftallyplugins' => (int) ($featureData['nooftallyplugins'] ?? 0),
                                'tallyplugins' => $featureData['tallyplugins'] ?? [],
                            ]
                        );
                    }
                }
            }

            // 5. Process Turnover Info (updateOrCreate per financial year row)
            if (!empty($item['turnoverinfo'])) {
                foreach ($item['turnoverinfo'] as $turnoverData) {
                    $serialNo = $turnoverData['tallyserialno'] ?? null;
                    $distName = $turnoverData['distname'] ?? null;

                    if ($serialNo && $distName && !empty($turnoverData['salesturnover'])) {
                        foreach ($turnoverData['salesturnover'] as $entry) {
                            $financialYear = $entry['financialyear'] ?? null;
                            $salesTurnover = $entry['salesturnover'] ?? 0;

                            if ($financialYear !== null) {
                                TurnoverInfo::updateOrCreate(
                                    [
                                        'tally_serial_no' => $serialNo,
                                        'dist_name' => $distName,
                                        'financial_year' => $financialYear,
                                    ],
                                    [
                                        'sales_turnover' => $salesTurnover,
                                    ]
                                );
                            }
                        }
                    }
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Processed successfully']);
    }

    /**
     * Sync distributor parameters with clean insert/delete logic.
     * Deletes parameters not in new payload, inserts new ones that don't exist.
     */
    private function syncDistributorParameters(
        ?string $tallySerial,
        ?string $principalId,
        ?string $distributorId,
        ?string $distName,
        array $newParameters
    ): void {
        if (!$tallySerial || !$principalId || !$distributorId || !$distName) {
            return;
        }

        $baseQuery = [
            'tallyserialno' => $tallySerial,
            'principalid' => $principalId,
            'distributorid' => $distributorId,
            'distname' => $distName,
        ];

        // Extract parameter hashes from new payload for comparison
        $newParamHashes = collect($newParameters)->map(function ($row) {
            return $this->hashParameterRow($row);
        })->toArray();

        // Get existing parameters
        $existingParams = DistributorParameter::where($baseQuery)->get();

        // Delete parameters not in new payload
        foreach ($existingParams as $existing) {
            $existingHash = $this->hashParameterRow($this->rowFromModel($existing));
            if (!in_array($existingHash, $newParamHashes)) {
                $existing->delete();
            }
        }

        // Insert new parameters
        foreach ($newParameters as $row) {
            $paramHash = $this->hashParameterRow($row);
            $rowExists = false;

            foreach ($existingParams as $existing) {
                if ($this->hashParameterRow($this->rowFromModel($existing)) === $paramHash) {
                    $rowExists = true;
                    break;
                }
            }

            if (!$rowExists) {
                $paramData = [];
                for ($i = 1; $i <= 10; $i++) {
                    $paramData['p' . $i] = $row['parm' . $i] ?? null;
                }
                DistributorParameter::create(array_merge($baseQuery, $paramData));
            }
        }
    }

    /**
     * Create a hash of parameter row for comparison.
     */
    private function hashParameterRow(array $row): string
    {
        $params = [];
        for ($i = 1; $i <= 10; $i++) {
            $params[] = $row['p' . $i] ?? $row['parm' . $i] ?? '';
        }
        return md5(implode('|', $params));
    }

    /**
     * Convert model to parameter array.
     */
    private function rowFromModel(DistributorParameter $model): array
    {
        $row = [];
        for ($i = 1; $i <= 10; $i++) {
            $row['p' . $i] = $model->{'p' . $i};
        }
        return $row;
    }

    /**
     * Recursively scan and convert all string "null" values to actual null in the payload.
     * Handles nested arrays and objects at any depth.
     */
    private function cleanNullStrings($data)
    {
        if (is_array($data)) {
            $cleaned = [];
            foreach ($data as $key => $value) {
                if (is_array($value) || is_object($value)) {
                    // Recursively clean nested arrays and objects
                    $cleaned[$key] = $this->cleanNullStrings($value);
                } elseif ($value === 'null' || $value === 'NULL') {
                    // Convert string "null" to actual null
                    $cleaned[$key] = null;
                } elseif (is_string($value) && trim($value) === '') {
                    // Convert empty strings to null
                    $cleaned[$key] = null;
                } else {
                    $cleaned[$key] = $value;
                }
            }
            return $cleaned;
        } elseif (is_object($data)) {
            // Handle objects
            $cleaned = new \stdClass();
            foreach ((array) $data as $key => $value) {
                if (is_array($value) || is_object($value)) {
                    $cleaned->{$key} = $this->cleanNullStrings($value);
                } elseif ($value === 'null' || $value === 'NULL') {
                    $cleaned->{$key} = null;
                } elseif (is_string($value) && trim($value) === '') {
                    $cleaned->{$key} = null;
                } else {
                    $cleaned->{$key} = $value;
                }
            }
            return $cleaned;
        } else {
            // Handle scalar values
            if ($data === 'null' || $data === 'NULL') {
                return null;
            } elseif (is_string($data) && trim($data) === '') {
                return null;
            }
            return $data;
        }
    }

    /**
     * Format date to Y-m-d for database storage.
     */
    private function formatDate($date)
    {
        if (empty($date)) return null;
        try {
            return \Carbon\Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
