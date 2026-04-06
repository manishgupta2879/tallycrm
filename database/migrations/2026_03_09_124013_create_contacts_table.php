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
        // Generic contacts table to be used for multiple entities (Distributors, Companies, etc.)
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->index();
            $table->string('parent_type', 50)->index(); // e.g., 'distributor'
            $table->string('name', 100)->nullable();
            $table->string('desig', 50)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('mobile', 15)->nullable();
            $table->string('loc', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
