<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\Lease;
use App\Models\PaymentRecord;
use Illuminate\Support\Facades\Auth;

class AuditLogObserver
{
    /**
     * Handle the Room "created" event.
     */
    public function created(Room|Tenant|Lease|PaymentRecord $model): void
    {
        $this->logAction('INSERT', $model);
    }

    /**
     * Handle the Room "updated" event.
     */
    public function updated(Room|Tenant|Lease|PaymentRecord $model): void
    {
        $this->logAction('UPDATE', $model);
    }

    /**
     * Handle the Room "deleted" event.
     */
    public function deleted(Room|Tenant|Lease|PaymentRecord $model): void
    {
        $this->logAction('DELETE', $model);
    }

    /**
     * Log the action to audit_log table
     */
    private function logAction(string $action, Room|Tenant|Lease|PaymentRecord $model): void
    {
        // Get current authenticated landlord
        $landlord = Auth::guard('web')->user();
        
        if (!$landlord) {
            // If no authenticated landlord, try to get the first landlord (fallback for seeding)
            $landlord = \App\Models\Landlord::first();
            if (!$landlord) {
                return; // Skip logging if no landlord context
            }
        }

        // Build description based on action and model
        $description = $this->buildDescription($action, $model);

        // Get the primary key value
        $recordId = $model->getKey();

        // Create audit log entry
        AuditLog::create([
            'landlord_id' => $landlord->landlord_id,
            'action' => $action,
            'table_name' => $model->getTable(),
            'record_id' => $recordId,
            'description' => $description,
        ]);
    }

    /**
     * Build description based on action and model
     */
    private function buildDescription(string $action, Room|Tenant|Lease|PaymentRecord $model): string
    {
        return match (class_basename($model)) {
            'Room' => $this->describeRoom($action, $model),
            'Tenant' => $this->describeTenant($action, $model),
            'Lease' => $this->describeLease($action, $model),
            'PaymentRecord' => $this->describePaymentRecord($action, $model),
            default => "{$action} on {$model->getTable()}",
        };
    }

    private function describeRoom(string $action, Room $room): string
    {
        return "{$action}: Room {$room->room_number} (Monthly Rent: ₱{$room->monthly_rent}, Status: {$room->status})";
    }

    private function describeTenant(string $action, Tenant $tenant): string
    {
        $name = $tenant->person?->first_name . ' ' . $tenant->person?->last_name;
        return "{$action}: Tenant {$name} (Status: {$tenant->status})";
    }

    private function describeLease(string $action, Lease $lease): string
    {
        $roomNumber = $lease->room?->room_number ?? 'N/A';
        return "{$action}: Lease for Room {$roomNumber} (Monthly Rent: ₱{$lease->monthly_rent}, Status: {$lease->status})";
    }

    private function describePaymentRecord(string $action, PaymentRecord $payment): string
    {
        return "{$action}: Payment ₱{$payment->total_amount} for Lease #{$payment->lease_id} (Status: {$payment->status})";
    }
}
