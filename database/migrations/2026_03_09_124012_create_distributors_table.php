<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Consolidated and optimized distributors table
        Schema::create('distributors', function (Blueprint $table) {
            $table->id();
            $table->string('code', 32)->unique()->index();       // Distributor Code
            $table->string('name', 100)->index();               // Distributor Name
            $table->string('type', 32)->nullable()->index();    // e.g., Wholesale, Retail
            $table->string('company_code', 32)->nullable()->index(); // Principal Company Code
            $table->text('address')->nullable();
            
            // Geo details (Length max 20)
            $table->string('country', 20)->nullable();
            $table->string('region', 20)->nullable();
            $table->string('state', 20)->nullable();
            $table->string('city', 20)->nullable();
            $table->string('pincode', 10)->nullable();
            
            $table->string('gst_number', 15)->nullable();
            $table->string('pan_number', 10)->nullable();
            
            // Tally details
            $table->string('tally_serial', 20)->nullable();
            $table->string('tally_version', 10)->nullable();
            $table->string('tally_release', 10)->nullable();
            $table->date('tally_expiry')->nullable();
            $table->string('tally_edition', 10)->nullable();
            $table->string('tally_net_id', 50)->nullable();
            $table->string('tcp_version', 10)->nullable();
            $table->string('tcp_source', 50)->nullable();
            $table->string('tally_users', 10)->nullable();
            $table->string('tally_deployed', 10)->default('cloud'); // cloud or local
            $table->string('no_of_computers', 10)->nullable();
            $table->string('existing_provider', 50)->nullable();
            $table->string('tally_data_volume', 20)->nullable();
            $table->boolean('tally_cloud')->default(1);
            
            // Rollout / Additional Details
            $table->date('rollout_request_date')->nullable();
            $table->date('tcp_generated_date')->nullable();
            $table->date('rollout_done_date')->nullable();
            $table->string('rollout_done_by', 50)->nullable();
            $table->text('rollout_remarks')->nullable();
            $table->date('remarks_date')->nullable();
            
            $table->string('status', 10)->default('Active')->index();
            $table->json('params')->nullable();                  // Dynamic parameters
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distributors');
    }
};
