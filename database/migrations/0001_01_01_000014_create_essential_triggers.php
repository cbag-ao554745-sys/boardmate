<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates 3 essential triggers for data integrity and operational compliance:
     * 1. Prevent duplicate active leases per tenant
     * 2. Prevent termination of leases with unpaid balances
     * 3. Recompute payment status considering current date
     */
    public function up(): void
    {
        // ============================================================
        // TRIGGER 1: Prevent Duplicate Active Leases Per Tenant
        // ============================================================
        // Business Rule: A tenant cannot have more than ONE active lease simultaneously.
        // Enforcement: BEFORE INSERT on lease_tenant
        // Raises: SQLSTATE 45000 if violation detected
        // Impact: Protects payment attribution (no ambiguity which lease a payment belongs to)
        // ============================================================
        DB::unprepared('
            CREATE TRIGGER trg_prevent_duplicate_active_leases
            BEFORE INSERT ON lease_tenant
            FOR EACH ROW
            BEGIN
                DECLARE v_active_lease_count INT;
                DECLARE v_tenant_lease_status VARCHAR(20);

                -- Check if this tenant is being added as primary to a lease
                IF NEW.is_primary_tenant = 1 THEN
                    -- Count active leases for this tenant
                    SELECT COUNT(lt.lease_tenant_id) INTO v_active_lease_count
                    FROM lease_tenant lt
                    JOIN lease l ON l.lease_id = lt.lease_id
                    WHERE lt.tenant_id = NEW.tenant_id
                      AND l.status = "Active"
                      AND lt.is_primary_tenant = 1;

                    -- If tenant already has an active primary lease, reject
                    IF v_active_lease_count > 0 THEN
                        SIGNAL SQLSTATE "45000"
                        SET MESSAGE_TEXT = "Tenant cannot have more than one active lease. Terminate the existing lease first.";
                    END IF;
                END IF;
            END
        ');

        // ============================================================
        // TRIGGER 2: Prevent Termination of Leases with Unpaid Balances
        // ============================================================
        // Business Rule: Active leases with outstanding payment balances cannot be
        //               terminated or completed.
        // Enforcement: BEFORE UPDATE on lease (checking status changes)
        // Raises: SQLSTATE 45000 if violation detected
        // Impact: Prevents financial loss, orphaned records, and audit trail breakage
        // ============================================================
        DB::unprepared('
            CREATE TRIGGER trg_prevent_lease_termination_with_unpaid
            BEFORE UPDATE ON lease
            FOR EACH ROW
            BEGIN
                DECLARE v_unpaid_balance DECIMAL(10, 2);
                DECLARE v_message VARCHAR(255);

                -- Only check if status is changing to Terminated or Completed
                IF OLD.status = "Active" AND NEW.status IN ("Terminated", "Completed") THEN
                    -- Sum all unpaid balances for this lease
                    SELECT COALESCE(SUM(pr.balance), 0) INTO v_unpaid_balance
                    FROM payment_record pr
                    WHERE pr.lease_id = NEW.lease_id
                      AND pr.balance > 0;

                    -- If there are unpaid balances, reject the termination
                    IF v_unpaid_balance > 0 THEN
                        SET v_message = "Cannot terminate lease with unpaid balance. Collect all payments before terminating.";
                        SIGNAL SQLSTATE "45000"
                        SET MESSAGE_TEXT = v_message;
                    END IF;
                END IF;
            END
        ');

        // ============================================================
        // TRIGGER 3: Atomic Payment Status Reconciliation with Overdue Date Check
        // ============================================================
        // Business Rule: Payment status must be recomputed considering BOTH
        //               remaining balance AND current date vs due date.
        // Enforcement: BEFORE INSERT on payment_record
        // Action: Sets status on NEW row based on balance and date
        // Impact: Ensures dashboard shows correct/current payment status; no stale statuses
        // ============================================================
        DB::unprepared('
            CREATE TRIGGER trg_recompute_payment_status_with_date
            BEFORE INSERT ON payment_record
            FOR EACH ROW
            BEGIN
                -- Compute the correct status based on balance and due date
                IF NEW.balance <= 0 THEN
                    SET NEW.status = "Paid";
                ELSEIF CURDATE() > NEW.bills_due_date AND NEW.balance > 0 THEN
                    SET NEW.status = "Overdue";
                ELSEIF NEW.amount_paid > 0 THEN
                    SET NEW.status = "Partial";
                ELSE
                    SET NEW.status = "Pending";
                END IF;
            END
        ');

        // ============================================================
        // TRIGGER 3b: Recompute on UPDATE (complements TRIGGER 3)
        // ============================================================
        DB::unprepared('
            CREATE TRIGGER trg_recompute_payment_status_with_date_on_update
            BEFORE UPDATE ON payment_record
            FOR EACH ROW
            BEGIN
                -- Compute the correct status based on balance and due date
                IF NEW.balance <= 0 THEN
                    SET NEW.status = "Paid";
                ELSEIF CURDATE() > NEW.bills_due_date AND NEW.balance > 0 THEN
                    SET NEW.status = "Overdue";
                ELSEIF NEW.amount_paid > 0 THEN
                    SET NEW.status = "Partial";
                ELSE
                    SET NEW.status = "Pending";
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_prevent_duplicate_active_leases');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_prevent_lease_termination_with_unpaid');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_recompute_payment_status_with_date');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_recompute_payment_status_with_date_on_update');
    }
};
