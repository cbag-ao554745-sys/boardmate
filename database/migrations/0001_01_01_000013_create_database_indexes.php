<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Create indexes for optimized query performance on frequently-searched columns.
     */
    public function up(): void
    {
        // Index on room table
        Schema::table('room', function (Blueprint $table) {
            if (!$this->indexExists('room', 'idx_room_status')) {
                $table->index('status', 'idx_room_status');
            }
        });

        // Index on tenant table
        Schema::table('tenant', function (Blueprint $table) {
            if (!$this->indexExists('tenant', 'idx_tenant_status')) {
                $table->index('status', 'idx_tenant_status');
            }
        });

        // Indexes on lease table
        Schema::table('lease', function (Blueprint $table) {
            if (!$this->indexExists('lease', 'idx_lease_room')) {
                $table->index('room_id', 'idx_lease_room');
            }
            if (!$this->indexExists('lease', 'idx_lease_status')) {
                $table->index('status', 'idx_lease_status');
            }
        });

        // Index on lease_tenant table
        Schema::table('lease_tenant', function (Blueprint $table) {
            if (!$this->indexExists('lease_tenant', 'idx_lt_tenant')) {
                $table->index('tenant_id', 'idx_lt_tenant');
            }
        });

        // Indexes on payment_record table
        Schema::table('payment_record', function (Blueprint $table) {
            if (!$this->indexExists('payment_record', 'idx_pr_lease')) {
                $table->index('lease_id', 'idx_pr_lease');
            }
            if (!$this->indexExists('payment_record', 'idx_pr_status')) {
                $table->index('status', 'idx_pr_status');
            }
            if (!$this->indexExists('payment_record', 'idx_pr_bills_due')) {
                $table->index('bills_due_date', 'idx_pr_bills_due');
            }
        });

        // Indexes on notification table
        Schema::table('notification', function (Blueprint $table) {
            if (!$this->indexExists('notification', 'idx_notif_landlord_read')) {
                $table->index(['landlord_id', 'is_read'], 'idx_notif_landlord_read');
            }
        });

        // Indexes on audit_log table
        Schema::table('audit_log', function (Blueprint $table) {
            if (!$this->indexExists('audit_log', 'idx_audit_landlord_time')) {
                $table->index(['landlord_id', 'timestamp'], 'idx_audit_landlord_time');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room', function (Blueprint $table) {
            if ($this->indexExists('room', 'idx_room_status')) {
                $table->dropIndex('idx_room_status');
            }
        });

        Schema::table('tenant', function (Blueprint $table) {
            if ($this->indexExists('tenant', 'idx_tenant_status')) {
                $table->dropIndex('idx_tenant_status');
            }
        });

        Schema::table('lease', function (Blueprint $table) {
            if ($this->indexExists('lease', 'idx_lease_room')) {
                $table->dropIndex('idx_lease_room');
            }
            if ($this->indexExists('lease', 'idx_lease_status')) {
                $table->dropIndex('idx_lease_status');
            }
        });

        Schema::table('lease_tenant', function (Blueprint $table) {
            if ($this->indexExists('lease_tenant', 'idx_lt_tenant')) {
                $table->dropIndex('idx_lt_tenant');
            }
        });

        Schema::table('payment_record', function (Blueprint $table) {
            if ($this->indexExists('payment_record', 'idx_pr_lease')) {
                $table->dropIndex('idx_pr_lease');
            }
            if ($this->indexExists('payment_record', 'idx_pr_status')) {
                $table->dropIndex('idx_pr_status');
            }
            if ($this->indexExists('payment_record', 'idx_pr_bills_due')) {
                $table->dropIndex('idx_pr_bills_due');
            }
        });

        Schema::table('notification', function (Blueprint $table) {
            if ($this->indexExists('notification', 'idx_notif_landlord_read')) {
                $table->dropIndex('idx_notif_landlord_read');
            }
        });

        Schema::table('audit_log', function (Blueprint $table) {
            if ($this->indexExists('audit_log', 'idx_audit_landlord_time')) {
                $table->dropIndex('idx_audit_landlord_time');
            }
        });
    }

    /**
     * Helper function to check if index exists
     */
    protected function indexExists($table, $indexName)
    {
        return \Illuminate\Support\Facades\Schema::hasIndex($table, $indexName);
    }
};
