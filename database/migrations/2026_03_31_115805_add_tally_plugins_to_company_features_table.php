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
        Schema::table('company_features', function (Blueprint $table) {
            $table->integer('nooftallyplugins')->default(0)->after('is_job_work_on')->nullable();
            $table->json('tallyplugins')->after('nooftallyplugins')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_features', function (Blueprint $table) {
            $table->dropColumn(['nooftallyplugins', 'tallyplugins']);
        });
    }
};
