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
        Schema::create('turnover_infos', function (Blueprint $table) {
            $table->id();
            $table->string('tally_serial_no', 50)->index();
            $table->string('dist_name')->index();
            $table->string('financial_year', 20);
            $table->decimal('sales_turnover', 18, 2)->default(0);
            $table->unique(['tally_serial_no', 'dist_name', 'financial_year'], 'turnover_unique_index');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turnover_infos');
    }
};
