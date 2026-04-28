<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Fix existing data that violates the GCash payment reference constraint.
     */
    public function up(): void
    {
        // Set payment_method to NULL for GCash payments without a reference
        DB::statement('
            UPDATE payment_record
            SET payment_method = NULL
            WHERE payment_method = "GCash" AND payment_reference IS NULL
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a data cleanup migration, no rollback action needed
        // The constraint migration will be rolled back separately if needed
    }
};
