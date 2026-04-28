<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'Initial Payment' to the payment_method enum
        DB::statement("ALTER TABLE payment_record MODIFY payment_method ENUM('Cash', 'GCash', 'Initial Payment') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'Initial Payment' from the enum
        DB::statement("ALTER TABLE payment_record MODIFY payment_method ENUM('Cash', 'GCash') NULL");
    }
};
