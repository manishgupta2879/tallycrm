<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Add indexes for optimal performance with large datasets (2M+ records)
     */
    public function up(): void
    {
        Schema::table('distributors', function (Blueprint $table) {
            // Index for serial number grouping and searching
            $table->index('tally_serial');

            // Index for date searching
            $table->index('tally_expiry');
            $table->index('last_sync_date');

            // Composite indexes for common query patterns
            $table->index(['company_code', 'status']); // Filter by company + status
            $table->index(['tally_serial', 'company_code']); // For grouping logic
            $table->index(['status', 'name']); // For listing and sorting
            $table->index(['company_code', 'tally_serial']); // For serial grouping per company

            // Index for search queries (FULLTEXT for larger text searches if needed)
            // Note: These are already indexed individually, but composite helps sorting
            $table->index(['code', 'name']);
            $table->index(['gst_number', 'pan_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distributors', function (Blueprint $table) {
            $table->dropIndex(['tally_serial']);
            $table->dropIndex(['tally_expiry']);
            $table->dropIndex(['last_sync_date']);
            $table->dropIndex(['company_code', 'status']);
            $table->dropIndex(['tally_serial', 'company_code']);
            $table->dropIndex(['status', 'name']);
            $table->dropIndex(['company_code', 'tally_serial']);
            $table->dropIndex(['code', 'name']);
            $table->dropIndex(['gst_number', 'pan_number']);
        });
    }
};
