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
        Schema::create('distributors', function (Blueprint $table) {
            $table->id();
            $table->string('pid')->unique();           // Distributor Code
            $table->string('name');                    // Distributor Name
            $table->string('distributor_type')->nullable(); // Distributor Type
            $table->string('company_pid')->nullable(); // Principal Company Code (from companies.pid)
            // Address Details
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pin_code')->nullable();
            $table->string('gst_no')->nullable();
            $table->string('pan_no')->nullable();
            // Contact Details
            $table->string('contact_name')->nullable();
            $table->string('designation')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            // Location
            $table->string('distributor_location')->nullable();
            $table->string('status')->default('Active');
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
