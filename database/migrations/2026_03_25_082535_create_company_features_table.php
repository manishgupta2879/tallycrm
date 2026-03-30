<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('company_features', function (Blueprint $table) {
            $table->id();
            $table->string('tally_serial_no', 100)->index();
            $table->string('dist_name')->index();
            $table->string('state_name', 25)->nullable();
            $table->string('country_name', 25)->nullable();
            $table->string('mobile_numbers', 100)->nullable();
            $table->string('corporate_identity_no', 50)->nullable();
            $table->string('income_tax_number', 50)->nullable();
            $table->string('is_tcs_on', 5)->nullable();
            $table->string('is_tds_on', 5)->nullable();
            $table->string('tan_number', 50)->nullable();
            $table->string('tds_deductor_type', 50)->nullable();
            $table->string('person_responsible_flat_no', 200)->nullable();
            $table->string('person_responsible_premises', 200)->nullable();
            $table->string('person_responsible_mobile', 100)->nullable();
            $table->string('person_responsible_phone', 100)->nullable();
            $table->string('person_responsible_email', 100)->nullable();
            $table->string('person_responsible_state', 100)->nullable();
            $table->string('is_gst_on', 5)->nullable();
            $table->string('gst_no', 15)->nullable();
            $table->string('gst_user_name', 100)->nullable();
            $table->string('gst_signing_mode', 50)->nullable();
            $table->string('is_e_invoice_applicable', 5)->nullable();
            $table->string('e_invoice_applicable_date', 15)->nullable();
            $table->string('e_invoice_bill_from_place', 100)->nullable();
            $table->string('is_e_way_bill_applicable', 5)->nullable();
            $table->string('e_way_bill_applicable_date', 15)->nullable();
            $table->string('e_way_bill_has_interstate', 5)->nullable();
            $table->string('e_way_bill_address_type', 50)->nullable();
            $table->string('e_way_bill_applicable_type', 50)->nullable();
            $table->string('e_way_bill_inter_state_threshold', 50)->nullable();
            $table->string('msme_enterprise_type', 50)->nullable();
            $table->string('msme_udyam_reg_no', 50)->nullable();
            $table->string('msme_activity_type', 50)->nullable();
            $table->string('is_accounting_on', 5)->nullable();
            $table->string('is_inventory_on', 5)->nullable();
            $table->string('is_integrated', 5)->nullable();
            $table->string('is_bill_wise_on', 5)->nullable();
            $table->string('is_cost_centres_on', 5)->nullable();
            $table->string('is_interest_on', 5)->nullable();
            $table->string('is_batch_wise_on', 5)->nullable();
            $table->string('is_perishable_on', 5)->nullable();
            $table->string('is_chq_printing_on', 5)->nullable();
            $table->string('use_zero_entries', 5)->nullable();
            $table->string('is_payroll_on', 5)->nullable();
            $table->string('is_discounts_on', 5)->nullable();
            $table->string('use_price_levels', 5)->nullable();
            $table->string('is_payment_request_on', 5)->nullable();
            $table->string('is_multi_address_on', 5)->nullable();
            $table->string('is_job_work_on', 5)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_features');
    }
};
