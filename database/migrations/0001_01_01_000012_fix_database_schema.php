<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Fix database schema mismatches between intended design and current migrations
     */
    public function up(): void
    {
        // Fix LEASE table: correct enum values and nullable constraints
        Schema::table('lease', function (Blueprint $table) {
            // Change status enum values from ['Active', 'Ended', 'Cancelled'] to ['Active', 'Completed', 'Terminated']
            DB::statement("ALTER TABLE lease MODIFY COLUMN status ENUM('Active', 'Completed', 'Terminated') NOT NULL DEFAULT 'Active'");
            
            // Fix deposit_amount: remove nullable, add default 0
            $table->decimal('deposit_amount', 10, 2)->default(0)->change();
            
            // Fix initial_payment: remove nullable, add default 0
            $table->decimal('initial_payment', 10, 2)->default(0)->change();
        });

        // Fix LEASE_TENANT table: make move_in_date NOT NULL
        Schema::table('lease_tenant', function (Blueprint $table) {
            $table->date('move_in_date')->change();
        });

        // Fix NOTIFICATION table: correct enum values
        Schema::table('notification', function (Blueprint $table) {
            // Change type enum values
            DB::statement("ALTER TABLE notification MODIFY COLUMN type ENUM('Due Soon', 'Overdue', 'Payment Received', 'System') NOT NULL DEFAULT 'System'");
        });

        // Fix AUDIT_LOG table: add missing column, fix action and description
        Schema::table('audit_log', function (Blueprint $table) {
            // Change action from string to enum
            DB::statement("ALTER TABLE audit_log MODIFY COLUMN action ENUM('INSERT', 'UPDATE', 'DELETE', 'LOGIN', 'LOGOUT') NOT NULL");
            
            // Fix description: make it NOT NULL
            DB::statement("ALTER TABLE audit_log MODIFY COLUMN description TEXT NOT NULL");
        });

        // Add table_name column to AUDIT_LOG if it doesn't exist
        if (!Schema::hasColumn('audit_log', 'table_name')) {
            Schema::table('audit_log', function (Blueprint $table) {
                $table->string('table_name', 50)->after('action');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert LEASE table changes
        Schema::table('lease', function (Blueprint $table) {
            DB::statement("ALTER TABLE lease MODIFY COLUMN status ENUM('Active', 'Ended', 'Cancelled') NOT NULL DEFAULT 'Active'");
            $table->decimal('deposit_amount', 10, 2)->nullable()->change();
            $table->decimal('initial_payment', 10, 2)->nullable()->change();
        });

        // Revert LEASE_TENANT table changes
        Schema::table('lease_tenant', function (Blueprint $table) {
            $table->date('move_in_date')->nullable()->change();
        });

        // Revert NOTIFICATION table changes
        Schema::table('notification', function (Blueprint $table) {
            DB::statement("ALTER TABLE notification MODIFY COLUMN type ENUM('Payment Reminder', 'Payment Overdue', 'Payment Received', 'System Alert') NOT NULL DEFAULT 'System Alert'");
        });

        // Revert AUDIT_LOG table changes
        Schema::table('audit_log', function (Blueprint $table) {
            DB::statement("ALTER TABLE audit_log MODIFY COLUMN action VARCHAR(100) NOT NULL");
            DB::statement("ALTER TABLE audit_log MODIFY COLUMN description TEXT DEFAULT NULL");
        });

        // Remove table_name column if it exists
        if (Schema::hasColumn('audit_log', 'table_name')) {
            Schema::table('audit_log', function (Blueprint $table) {
                $table->dropColumn('table_name');
            });
        }
    }
};
