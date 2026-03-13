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
        Schema::table('companies', function (Blueprint $table) {
            $table->json('d_types')->nullable();
            $table->json('d_parameter')->nullable();
            $table->json('c_urls')->nullable();
        });

        Schema::table('distributors', function (Blueprint $table) {
            $table->json('d_parameters')->nullable();
            $table->json('c_urls')->nullable(); // Using c_urls as requested by user
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['d_types', 'd_parameter', 'c_urls']);
        });

        Schema::table('distributors', function (Blueprint $table) {
            $table->dropColumn(['d_parameters', 'c_urls']);
        });
    }
};
