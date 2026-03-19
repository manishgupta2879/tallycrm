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
        Schema::create('additional_opportunity', function (Blueprint $table) {
            $table->id();
                $table->string('company_name');
                $table->unsignedBigInteger('category_id');
                $table->string('description')->nullable();
                // $table->foreign('category_id')->references('id')->on('category')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('additional_opportunity');
    }
};
