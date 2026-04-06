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
            $table->string('code', 32)->index(); // Distributor Code
            $table->string('name', 100)->index(); // Distributor Name
            $table->string('type', 32)->nullable()->index(); // e.g., Wholesale, Retail
            $table->string('company_code', 32)->nullable()->index(); // Principal Company Code
            $table->text('address')->nullable();

            // Geo details (as string fields now)
            $table->string('country', 100)->nullable();
            $table->string('state', 100)->nullable();

            $table->string('gst_number', 15)->nullable();
            $table->string('pan_number', 15)->nullable();

            $table->string('tan_no', 50)->nullable();
            $table->string('msme_no', 50)->nullable();


            // Distributor Params (Max 10)
            // for ($i = 1; $i <= 10; $i++) {
            //     $table->string('d_parameter_' . $i, 100)->nullable();
            // }

            // Tally details
            $table->string('tally_serial', 50)->nullable();
            $table->string('tally_version',50)->nullable();
            $table->string('tally_release',50)->nullable();
            $table->string('tally_expiry',15)->nullable();
            $table->string('tally_edition',50)->nullable();
            $table->string('tally_net_id',50)->nullable();

            $table->string('tally_users',50)->nullable();
            $table->string('tally_deployed',6)->default('cloud'); // cloud or local
            $table->string('no_of_computers',20)->nullable();
            $table->string('existing_provider',100)->nullable();
            $table->string('tally_data_volume',50)->nullable();
            $table->boolean('tally_cloud')->default(1);

            // Sync information
            $table->text('dist_perm_pass')->nullable();
            $table->string('last_sync_date',15)->nullable();
            $table->string('no_of_sync_urls',5)->nullable();
            $table->json('c_urls')->nullable();


            // Rollout / Additional Details
            $table->string('rollout_request_date',15)->nullable();
            $table->string('tcp_generated_date',15)->nullable();
            $table->string('rollout_done_date',15)->nullable();
            $table->string('rollout_done_by',100)->nullable();
            $table->text('rollout_remarks')->nullable();
            $table->string('remarks_date',15)->nullable();

            $table->string('status',9)->default('Active')->index();
            $table->json('params')->nullable();
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
