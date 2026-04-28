<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Create triggers for automatic payment computation, status updates, and room/lease status synchronization.
     */
    public function up(): void
    {
        // Trigger 1: Auto-compute total_amount and balance on INSERT
        DB::unprepared('
            CREATE TRIGGER trg_payment_record_before_insert
            BEFORE INSERT ON payment_record
            FOR EACH ROW
            BEGIN
                SET NEW.total_amount = NEW.rent_amount
                                     + NEW.electricity_amount
                                     + NEW.water_amount
                                     + NEW.other_fees;

                SET NEW.balance = NEW.total_amount - NEW.amount_paid;

                IF NEW.balance <= 0 THEN
                    SET NEW.status = "Paid";
                    SET NEW.date_paid = IFNULL(NEW.date_paid, NOW());
                ELSEIF NEW.amount_paid > 0 THEN
                    SET NEW.status = "Partial";
                ELSEIF NEW.bills_due_date < CURDATE() THEN
                    SET NEW.status = "Overdue";
                ELSE
                    SET NEW.status = "Pending";
                END IF;
            END
        ');

        // Trigger 2: Recompute total_amount, balance, and status on UPDATE
        DB::unprepared('
            CREATE TRIGGER trg_payment_record_before_update
            BEFORE UPDATE ON payment_record
            FOR EACH ROW
            BEGIN
                SET NEW.total_amount = NEW.rent_amount
                                     + NEW.electricity_amount
                                     + NEW.water_amount
                                     + NEW.other_fees;

                SET NEW.balance = NEW.total_amount - NEW.amount_paid;

                IF NEW.balance <= 0 THEN
                    SET NEW.status = "Paid";
                    SET NEW.date_paid = IFNULL(NEW.date_paid, NOW());
                ELSEIF NEW.amount_paid > 0 THEN
                    SET NEW.status = "Partial";
                ELSEIF NEW.bills_due_date < CURDATE() THEN
                    SET NEW.status = "Overdue";
                ELSE
                    SET NEW.status = "Pending";
                END IF;
            END
        ');

        // Trigger 3: Create notification when payment status becomes "Paid"
        DB::unprepared('
            CREATE TRIGGER trg_payment_record_after_update_notify
            AFTER UPDATE ON payment_record
            FOR EACH ROW
            BEGIN
                DECLARE v_landlord_id INT UNSIGNED;

                IF OLD.status != "Paid" AND NEW.status = "Paid" THEN
                    SELECT l.landlord_id
                      INTO v_landlord_id
                      FROM lease l
                     WHERE l.lease_id = NEW.lease_id
                     LIMIT 1;

                    INSERT INTO notification (
                        payment_id, landlord_id, type, message, sent_at, is_read
                    )
                    VALUES (
                        NEW.payment_id,
                        v_landlord_id,
                        "Payment Received",
                        CONCAT(
                            "Payment FULLY received for Lease #", NEW.lease_id,
                            " amounting to ₱", FORMAT(NEW.amount_paid, 2),
                            " on ", DATE_FORMAT(NEW.date_paid, "%M %d, %Y"), "."
                        ),
                        NOW(),
                        0
                    );
                END IF;
            END
        ');

        // Trigger 4: Auto-update Room status when Lease status changes to Active/Completed/Terminated
        DB::unprepared('
            CREATE TRIGGER trg_lease_after_update_room_status
            AFTER UPDATE ON lease
            FOR EACH ROW
            BEGIN
                IF OLD.status != NEW.status THEN
                    IF NEW.status = "Active" THEN
                        UPDATE room SET status = "Occupied" WHERE room_id = NEW.room_id;
                    ELSEIF NEW.status IN ("Completed", "Terminated") THEN
                        UPDATE room SET status = "Available" WHERE room_id = NEW.room_id;
                    END IF;
                END IF;
            END
        ');

        // Trigger 5: Auto-set Room to Occupied when new Active Lease is inserted
        DB::unprepared('
            CREATE TRIGGER trg_lease_after_insert_room_status
            AFTER INSERT ON lease
            FOR EACH ROW
            BEGIN
                IF NEW.status = "Active" THEN
                    UPDATE room SET status = "Occupied" WHERE room_id = NEW.room_id;
                END IF;
            END
        ');

        // Trigger 6: Enforce only ONE primary tenant per lease on INSERT
        DB::unprepared('
            CREATE TRIGGER trg_lease_tenant_before_insert_primary
            BEFORE INSERT ON lease_tenant
            FOR EACH ROW
            BEGIN
                DECLARE v_primary_count INT;

                IF NEW.is_primary_tenant = 1 THEN
                    SELECT COUNT(*) INTO v_primary_count
                      FROM lease_tenant
                     WHERE lease_id = NEW.lease_id
                       AND is_primary_tenant = 1;

                    IF v_primary_count >= 1 THEN
                        SIGNAL SQLSTATE "45000"
                        SET MESSAGE_TEXT = "A lease can only have one primary tenant.";
                    END IF;
                END IF;
            END
        ');

        // Trigger 7: Enforce only ONE primary tenant per lease on UPDATE
        DB::unprepared('
            CREATE TRIGGER trg_lease_tenant_before_update_primary
            BEFORE UPDATE ON lease_tenant
            FOR EACH ROW
            BEGIN
                DECLARE v_primary_count INT;

                IF NEW.is_primary_tenant = 1 AND OLD.is_primary_tenant = 0 THEN
                    SELECT COUNT(*) INTO v_primary_count
                      FROM lease_tenant
                     WHERE lease_id = NEW.lease_id
                       AND is_primary_tenant = 1
                       AND lease_tenant_id != NEW.lease_tenant_id;

                    IF v_primary_count >= 1 THEN
                        SIGNAL SQLSTATE "45000"
                        SET MESSAGE_TEXT = "A lease can only have one primary tenant.";
                    END IF;
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_payment_record_before_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_payment_record_before_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_payment_record_after_update_notify');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_lease_after_update_room_status');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_lease_after_insert_room_status');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_lease_tenant_before_insert_primary');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_lease_tenant_before_update_primary');
    }
};
