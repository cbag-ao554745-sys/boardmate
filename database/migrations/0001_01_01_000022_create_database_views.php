<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates all database views for reporting and dashboard functionality:
     * - vw_active_leases: Active lease details with primary tenant
     * - vw_tenant_directory: Complete tenant profiles with guardians
     * - vw_payment_ledger: Full payment history per lease
     * - vw_overdue_accounts: Overdue or partial-past-due payments
     * - vw_payment_summary_by_month: Monthly collection aggregates
     * - vw_unread_notifications: Pending notifications for dashboard
     * - vw_room_occupancy_status: Current occupancy status per room
     */
    public function up(): void
    {
        // ============================================================
        // VIEW 1: vw_active_leases
        // Full picture of each active lease: room, tenants, landlord.
        // Usage: Landlord dashboard overview.
        // ============================================================
        DB::statement(<<<'SQL'
            CREATE OR REPLACE VIEW vw_active_leases AS
            SELECT
                l.lease_id,
                l.status                                        AS lease_status,
                r.room_number,
                r.floor,
                l.monthly_rent,
                l.deposit_amount,
                l.payment_due_day,
                l.start_date,
                l.end_date,
                -- Primary tenant details
                CONCAT(pp.first_name, ' ',
                       IFNULL(CONCAT(pp.middle_name, ' '), ''),
                       pp.last_name)                            AS primary_tenant_name,
                pp.contact_number                               AS primary_tenant_contact,
                lt.move_in_date,
                lt.move_out_date,
                -- Landlord
                CONCAT(lp.first_name, ' ', lp.last_name)       AS landlord_name
            FROM lease l
            JOIN room        r  ON r.room_id    = l.room_id
            JOIN landlord    ld ON ld.landlord_id = l.landlord_id
            JOIN person      lp ON lp.person_id  = ld.person_id
            JOIN lease_tenant lt ON lt.lease_id = l.lease_id
                                 AND lt.is_primary_tenant = 1
            JOIN tenant      t  ON t.tenant_id  = lt.tenant_id
            JOIN person      pp ON pp.person_id = t.person_id
            WHERE l.status = 'Active'
        SQL);

        // ============================================================
        // VIEW 2: vw_tenant_directory
        // Complete tenant profile with guardian info.
        // Usage: Tenant management / search screen.
        // ============================================================
        DB::statement(<<<'SQL'
            CREATE OR REPLACE VIEW vw_tenant_directory AS
            SELECT
                t.tenant_id,
                t.status                                            AS tenant_status,
                CONCAT(p.first_name, ' ',
                       IFNULL(CONCAT(p.middle_name, ' '), ''),
                       p.last_name)                                 AS full_name,
                p.date_of_birth,
                p.gender,
                p.contact_number,
                CONCAT_WS(', ',
                    NULLIF(TRIM(CONCAT_WS(' ', p.address_line_1, p.address_line_2)), ''),
                    p.city, p.province, p.postal_code)              AS full_address,
                -- Guardian
                CONCAT(gp.first_name, ' ', gp.last_name)            AS guardian_name,
                gp.contact_number                                   AS guardian_contact,
                -- Onboarded by
                CONCAT(lp.first_name, ' ', lp.last_name)            AS onboarded_by,
                t.created_at                                        AS onboarded_at
            FROM tenant   t
            JOIN person   p  ON p.person_id  = t.person_id
            JOIN landlord ld ON ld.landlord_id = t.landlord_id
            JOIN person   lp ON lp.person_id  = ld.person_id
            LEFT JOIN person gp ON gp.person_id = t.guardian_person_id
        SQL);

        // ============================================================
        // VIEW 3: vw_payment_ledger
        // Full payment history per lease with tenant and room context.
        // Usage: Payment history screen / per-lease ledger.
        // ============================================================
        DB::statement(<<<'SQL'
            CREATE OR REPLACE VIEW vw_payment_ledger AS
            SELECT
                pr.payment_id,
                r.room_number,
                l.lease_id,
                CONCAT(p.first_name, ' ', p.last_name)      AS paid_by,
                pr.rent_amount,
                pr.electricity_amount,
                pr.water_amount,
                pr.other_fees,
                pr.total_amount,
                pr.amount_paid,
                pr.balance,
                pr.payment_method,
                pr.payment_reference,
                pr.status                                   AS payment_status,
                pr.bills_due_date,
                pr.date_paid,
                pr.created_at
            FROM payment_record pr
            JOIN lease   l  ON l.lease_id   = pr.lease_id
            JOIN room    r  ON r.room_id    = l.room_id
            JOIN tenant  t  ON t.tenant_id  = pr.tenant_id
            JOIN person  p  ON p.person_id  = t.person_id
            ORDER BY pr.bills_due_date DESC, pr.created_at DESC
        SQL);

        // ============================================================
        // VIEW 4: vw_overdue_accounts
        // All payment records that are Overdue or Partial past due date.
        // Usage: Overdue monitoring panel / notification generation.
        // ============================================================
        DB::statement(<<<'SQL'
            CREATE OR REPLACE VIEW vw_overdue_accounts AS
            SELECT
                pr.payment_id,
                r.room_number,
                l.lease_id,
                CONCAT(p.first_name, ' ', p.last_name)          AS tenant_name,
                p.contact_number                                AS tenant_contact,
                pr.total_amount,
                pr.amount_paid,
                pr.balance,
                pr.status,
                pr.bills_due_date,
                DATEDIFF(CURDATE(), pr.bills_due_date)          AS days_overdue,
                CONCAT(lp.first_name, ' ', lp.last_name)        AS landlord_name
            FROM payment_record pr
            JOIN lease   l  ON l.lease_id   = pr.lease_id
            JOIN room    r  ON r.room_id    = l.room_id
            JOIN tenant  t  ON t.tenant_id  = pr.tenant_id
            JOIN person  p  ON p.person_id  = t.person_id
            JOIN landlord ld ON ld.landlord_id = l.landlord_id
            JOIN person  lp ON lp.person_id  = ld.person_id
            WHERE pr.status IN ('Overdue', 'Partial')
              AND pr.bills_due_date < CURDATE()
            ORDER BY days_overdue DESC
        SQL);

        // ============================================================
        // VIEW 5: vw_payment_summary_by_month
        // Aggregated monthly collection report.
        // Usage: Financial reporting / income summary.
        // ============================================================
        DB::statement(<<<'SQL'
            CREATE OR REPLACE VIEW vw_payment_summary_by_month AS
            SELECT
                YEAR(pr.date_paid)                          AS payment_year,
                MONTH(pr.date_paid)                         AS payment_month,
                DATE_FORMAT(pr.date_paid, '%M %Y')          AS period_label,
                r.room_number,
                COUNT(pr.payment_id)                        AS payment_count,
                SUM(pr.total_amount)                        AS total_billed,
                SUM(pr.amount_paid)                         AS total_collected,
                SUM(pr.balance)                             AS total_balance_remaining
            FROM payment_record pr
            JOIN lease  l ON l.lease_id = pr.lease_id
            JOIN room   r ON r.room_id  = l.room_id
            WHERE pr.status = 'Paid'
            GROUP BY YEAR(pr.date_paid), MONTH(pr.date_paid), DATE_FORMAT(pr.date_paid, '%M %Y'), r.room_number
            ORDER BY
                payment_year DESC,
                payment_month DESC,
                r.room_number
        SQL);

        // ============================================================
        // VIEW 6: vw_unread_notifications
        // Pending notifications for the landlord dashboard.
        // Usage: Notification bell / alert panel.
        // ============================================================
        DB::statement(<<<'SQL'
            CREATE OR REPLACE VIEW vw_unread_notifications AS
            SELECT
                n.notification_id,
                n.landlord_id,
                n.type,
                n.message,
                n.sent_at,
                -- Linked payment context (if any)
                pr.payment_id,
                r.room_number,
                pr.bills_due_date,
                pr.status               AS payment_status
            FROM notification n
            LEFT JOIN payment_record pr ON pr.payment_id = n.payment_id
            LEFT JOIN lease          l  ON l.lease_id    = pr.lease_id
            LEFT JOIN room           r  ON r.room_id     = l.room_id
            WHERE n.is_read = 0
            ORDER BY n.sent_at DESC
        SQL);

        // ============================================================
        // VIEW 7: vw_room_occupancy_status
        // Current occupancy and active lease info per room.
        // Usage: Room management / availability screen.
        // ============================================================
        DB::statement(<<<'SQL'
            CREATE OR REPLACE VIEW vw_room_occupancy_status AS
            SELECT
                r.room_id,
                r.room_number,
                r.floor,
                r.monthly_rent          AS listed_rent,
                r.status                AS room_status,
                l.lease_id,
                l.monthly_rent          AS lease_rent,
                l.start_date,
                l.payment_due_day,
                CONCAT(p.first_name, ' ', p.last_name)  AS primary_tenant,
                p.contact_number        AS tenant_contact,
                lt.move_in_date
            FROM room r
            LEFT JOIN lease        l  ON l.room_id    = r.room_id AND l.status = 'Active'
            LEFT JOIN lease_tenant lt ON lt.lease_id  = l.lease_id AND lt.is_primary_tenant = 1
            LEFT JOIN tenant       t  ON t.tenant_id  = lt.tenant_id
            LEFT JOIN person       p  ON p.person_id  = t.person_id
            ORDER BY r.floor, r.room_number
        SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS vw_room_occupancy_status');
        DB::statement('DROP VIEW IF EXISTS vw_unread_notifications');
        DB::statement('DROP VIEW IF EXISTS vw_payment_summary_by_month');
        DB::statement('DROP VIEW IF EXISTS vw_overdue_accounts');
        DB::statement('DROP VIEW IF EXISTS vw_payment_ledger');
        DB::statement('DROP VIEW IF EXISTS vw_tenant_directory');
        DB::statement('DROP VIEW IF EXISTS vw_active_leases');
    }
};

