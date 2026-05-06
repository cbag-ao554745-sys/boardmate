<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates 3 essential views for landlord operational intelligence and strategic planning:
     * 1. vw_lease_payment_health_dashboard - Per-lease payment overview and collection metrics
     * 2. vw_tenant_delinquency_profile - Tenant payment history and risk assessment
     * 3. vw_landlord_cash_flow_forecast - 90-day payment due date forecast for planning
     */
    public function up(): void
    {
        // ============================================================
        // VIEW 1: vw_lease_payment_health_dashboard
        // Shows per-lease: room, primary tenant, rent, payment distribution,
        // total_owed, days_overdue, and collection rate (%).
        // Usage: Landlord homepage — identify which leases need attention
        // ============================================================
        DB::statement(<<<'SQL'
            CREATE OR REPLACE VIEW vw_lease_payment_health_dashboard AS
            SELECT
                l.lease_id,
                r.room_number,
                r.floor,
                CONCAT(p.first_name, ' ', p.last_name) AS primary_tenant_name,
                p.contact_number AS tenant_contact,
                l.monthly_rent,
                l.payment_due_day,
                l.status AS lease_status,
                l.start_date,
                
                -- Payment distribution counts
                COUNT(CASE WHEN pr.status = 'Pending' THEN 1 END) AS pending_count,
                COUNT(CASE WHEN pr.status = 'Partial' THEN 1 END) AS partial_count,
                COUNT(CASE WHEN pr.status = 'Paid' THEN 1 END) AS paid_count,
                COUNT(CASE WHEN pr.status = 'Overdue' THEN 1 END) AS overdue_count,
                
                -- Financial metrics
                SUM(CASE WHEN pr.balance > 0 THEN pr.balance ELSE 0 END) AS total_owed,
                MAX(CASE WHEN pr.status IN ('Overdue', 'Partial') THEN DATEDIFF(CURDATE(), pr.bills_due_date) ELSE 0 END) AS days_overdue_max,
                ROUND(
                    SUM(CASE WHEN pr.status = 'Paid' THEN pr.amount_paid ELSE 0 END) /
                    NULLIF(SUM(pr.total_amount), 0) * 100,
                    2
                ) AS collection_rate_percent
                
            FROM lease l
            JOIN room r ON r.room_id = l.room_id
            JOIN lease_tenant lt ON lt.lease_id = l.lease_id AND lt.is_primary_tenant = 1
            JOIN tenant t ON t.tenant_id = lt.tenant_id
            JOIN person p ON p.person_id = t.person_id
            LEFT JOIN payment_record pr ON pr.lease_id = l.lease_id
            
            WHERE l.status = 'Active'
            GROUP BY l.lease_id, r.room_number, r.floor, p.first_name, p.last_name, p.contact_number,
                     l.monthly_rent, l.payment_due_day, l.status, l.start_date
            ORDER BY days_overdue_max DESC, total_owed DESC
        SQL);

        // ============================================================
        // VIEW 2: vw_tenant_delinquency_profile
        // Aggregates tenant payment behavior across ALL leases (active + completed).
        // Shows: total_outstanding, overdue_instances, payment_history, red flags.
        // Usage: Pre-approval check before signing new lease — is tenant chronically late?
        // ============================================================
        DB::statement(<<<'SQL'
            CREATE OR REPLACE VIEW vw_tenant_delinquency_profile AS
            SELECT
                t.tenant_id,
                CONCAT(p.first_name, ' ', p.last_name) AS tenant_name,
                p.contact_number,
                t.status AS tenant_status,
                
                -- Lease history
                COUNT(DISTINCT l.lease_id) AS total_lease_count,
                COUNT(DISTINCT CASE WHEN l.status = 'Active' THEN l.lease_id END) AS active_lease_count,
                
                -- Payment obligations
                SUM(CASE WHEN pr.balance > 0 THEN pr.balance ELSE 0 END) AS total_outstanding,
                COUNT(DISTINCT CASE WHEN pr.status = 'Overdue' THEN pr.payment_id END) AS overdue_instances,
                COUNT(DISTINCT CASE WHEN pr.status = 'Partial' AND pr.bills_due_date < CURDATE() THEN pr.payment_id END) AS partial_overdue_instances,
                
                -- Payment reliability score
                MAX(pr.date_paid) AS most_recent_payment_date,
                MIN(CASE WHEN pr.status IN ('Overdue', 'Partial') AND pr.bills_due_date < CURDATE() THEN DATEDIFF(CURDATE(), pr.bills_due_date) ELSE 0 END) AS days_late_worst,
                
                -- Risk assessment: if currently blacklisted
                CASE WHEN t.status = 'Blacklisted' THEN 'HIGH' WHEN t.status = 'Inactive' THEN 'MEDIUM' ELSE 'ACTIVE' END AS current_status_flag
                
            FROM tenant t
            JOIN person p ON p.person_id = t.person_id
            LEFT JOIN lease l ON l.lease_id = (
                SELECT lease_id FROM lease_tenant
                WHERE tenant_id = t.tenant_id
                ORDER BY lease_tenant_id DESC
                LIMIT 1
            )
            LEFT JOIN payment_record pr ON pr.lease_id = l.lease_id AND pr.tenant_id = t.tenant_id
            
            GROUP BY t.tenant_id, p.first_name, p.last_name, p.contact_number, t.status
            ORDER BY total_outstanding DESC, overdue_instances DESC
        SQL);

        // ============================================================
        // VIEW 3: vw_landlord_cash_flow_forecast
        // Projected collections for next 90 days grouped by payment due date.
        // Shows: rooms due that day, expected_revenue, collected, amount_at_risk.
        // Usage: Landlord monthly planning — "Will I have PHP 50,000 by May 15?"
        // ============================================================
        DB::statement(<<<'SQL'
            CREATE OR REPLACE VIEW vw_landlord_cash_flow_forecast AS
            SELECT
                pr.bills_due_date,
                DATEDIFF(pr.bills_due_date, CURDATE()) AS days_until_due,
                
                -- Room/lease counts due that date
                COUNT(DISTINCT pr.lease_id) AS lease_count_due,
                COUNT(DISTINCT r.room_id) AS room_count_due,
                
                -- Financial projections
                SUM(pr.total_amount) AS total_due,
                SUM(CASE WHEN pr.status = 'Paid' THEN pr.amount_paid ELSE 0 END) AS already_collected,
                SUM(CASE WHEN pr.status IN ('Pending', 'Partial', 'Overdue') THEN pr.balance ELSE 0 END) AS amount_at_risk,
                
                -- Risk breakdown
                COUNT(CASE WHEN pr.status = 'Paid' THEN 1 END) AS payment_count_collected,
                COUNT(CASE WHEN pr.status = 'Partial' THEN 1 END) AS payment_count_partial,
                COUNT(CASE WHEN pr.status IN ('Pending', 'Overdue') THEN 1 END) AS payment_count_pending,
                
                -- Optimization: if today is past due date, mark as critical
                CASE
                    WHEN pr.bills_due_date < CURDATE() THEN 'OVERDUE'
                    WHEN pr.bills_due_date = CURDATE() THEN 'TODAY'
                    WHEN DATEDIFF(pr.bills_due_date, CURDATE()) <= 3 THEN 'CRITICAL'
                    ELSE 'NORMAL'
                END AS priority_flag
                
            FROM payment_record pr
            JOIN lease l ON l.lease_id = pr.lease_id
            JOIN room r ON r.room_id = l.room_id
            
            WHERE pr.bills_due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 90 DAY)
            
            GROUP BY pr.bills_due_date
            ORDER BY pr.bills_due_date ASC
        SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS vw_landlord_cash_flow_forecast');
        DB::statement('DROP VIEW IF EXISTS vw_tenant_delinquency_profile');
        DB::statement('DROP VIEW IF EXISTS vw_lease_payment_health_dashboard');
    }
};
