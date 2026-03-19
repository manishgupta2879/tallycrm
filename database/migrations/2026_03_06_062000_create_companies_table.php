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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('pid')->unique(); // Company Code e.g., SONY001
            $table->string('name');
            // Contact Person
            $table->string('contact_name')->nullable();
            $table->string('designation')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            // Territory / Area of Operations
            $table->string('territory')->nullable();
            $table->json('d_types')->nullable();
            $table->json('d_parameter')->nullable();
            $table->json('c_urls')->nullable();
            $table->integer('no_of_urls')->default(0);
            $table->string('status')->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
