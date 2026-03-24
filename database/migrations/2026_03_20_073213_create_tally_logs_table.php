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
        Schema::create('tally_logs', function (Blueprint $table) {
            $table->id();
            $table->string('pid')->nullable();
            $table->string('distributor_id')->nullable();
            $table->string('tally_serial_no');
            $table->string('tally_version');
            $table->string('tally_release');
            $table->string('tally_edition');
            $table->string('account_id');
            $table->string('tss_expiry_date');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tally_logs');
    }
};
