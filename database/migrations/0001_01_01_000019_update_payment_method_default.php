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
        Schema::table('payment_record', function (Blueprint $table) {
            // Change payment_method from nullable to NOT NULL with default 'Cash'
            $table->enum('payment_method', ['Cash', 'GCash'])
                ->default('Cash')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_record', function (Blueprint $table) {
            // Revert to nullable
            $table->enum('payment_method', ['Cash', 'GCash'])
                ->nullable()
                ->change();
        });
    }
};
