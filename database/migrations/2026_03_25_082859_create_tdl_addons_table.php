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
        Schema::create('tdl_addons', function (Blueprint $table) {
            $table->id();
            $table->string('tally_serial_no',50);
            $table->string('tcp_filename');
            $table->string('tcp_filepath');
            $table->string('tcp_file_format')->nullable();
            $table->string('tcp_version')->nullable();
            $table->string('tcp_expiry_date')->nullable();
            $table->string('tcp_source_type')->nullable();
            $table->string('tcp_author_name')->nullable();
            $table->string('tcp_author_email_id')->nullable();
            $table->string('tcp_author_website')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tdl_addons');
    }
};
