<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Casts\Attribute;

class CompanyFeature extends Model
{
    protected $fillable = [
        'tally_serial_no',
        'dist_name',
        'corporate_identity_no',
        'income_tax_number',
        'is_tcs_on',
        'is_tds_on',
        'tan_number',
        'tds_deductor_type',
        'person_responsible_flat_no',
        'person_responsible_premises',
        'person_responsible_mobile',
        'person_responsible_phone',
        'person_responsible_email',
        'person_responsible_state',
        'is_gst_on',
        'gst_no',
        'gst_user_name',
        'gst_signing_mode',
        'is_e_invoice_applicable',
        'e_invoice_applicable_date',
        'e_invoice_bill_from_place',
        'is_e_way_bill_applicable',
        'e_way_bill_applicable_date',
        'e_way_bill_has_interstate',
        'e_way_bill_address_type',
        'e_way_bill_applicable_type',
        'e_way_bill_inter_state_threshold',
        'msme_enterprise_type',
        'msme_udyam_reg_no',
        'msme_activity_type',
        'is_accounting_on',
        'is_inventory_on',
        'is_integrated',
        'is_bill_wise_on',
        'is_cost_centres_on',
        'is_interest_on',
        'is_batch_wise_on',
        'is_perishable_on',
        'is_chq_printing_on',
        'use_zero_entries',
        'is_payroll_on',
        'is_discounts_on',
        'use_price_levels',
        'is_payment_request_on',
        'is_multi_address_on',
        'is_job_work_on',
        'nooftallyplugins',
        'tallyplugins',
    ];

    protected $casts = [
        'tallyplugins' => 'array',
    ];

    /**
     * Accessors and Mutators for formatted dates
     */
    protected function eInvoiceApplicableDate(): Attribute
    {
        return Attribute::make(
            get: fn($v) => $this->formatDate($v),
            set: fn($v) => $this->formatDateSet($v)
        );
    }

    protected function eWayBillApplicableDate(): Attribute
    {
        return Attribute::make(
            get: fn($v) => $this->formatDate($v),
            set: fn($v) => $this->formatDateSet($v)
        );
    }

    private function formatDate($value)
    {
        if (!$value) return $value;
        try {
            if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $value)) return $value;
            return \Carbon\Carbon::parse($value)->format('d/m/Y');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function formatDateSet($value)
    {
        if (empty($value)) return null;
        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    public function setMsmeEnterpriseTypeAttribute($value)
    {
        $this->attributes['msme_enterprise_type'] =
            trim(preg_replace('/[\x00-\x1F\x7F]/u', '', $value));
    }
}
