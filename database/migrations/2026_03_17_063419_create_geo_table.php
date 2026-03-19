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
        Schema::create('geo', function (Blueprint $table) {
            $table->increments('geo_id');
            $table->string('pid')->nullable();
            $table->string('rid')->default('0')->nullable();
            $table->string('id')->nullable()->index('PRE_Geo_ID');
            $table->string('name')->nullable();
            $table->string('nature')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_companygeodetails');
    }
};
