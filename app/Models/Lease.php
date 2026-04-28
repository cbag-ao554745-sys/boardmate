<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class Lease extends Model
{
    use HasFactory;

    protected $table = 'lease';
    protected $primaryKey = 'lease_id';

    protected $fillable = [
        'room_id', 
        'landlord_id', 
        'start_date', 
        'end_date', 
        'monthly_rent', 
        'deposit_amount', 
        'initial_payment', 
        'payment_due_day', 
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'monthly_rent' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'initial_payment' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    const STATUS_ACTIVE = 'Active';
    const STATUS_COMPLETED = 'Completed';
    const STATUS_TERMINATED = 'Terminated';

    // Relationships

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }

    public function landlord(): BelongsTo
    {
        return $this->belongsTo(Landlord::class, 'landlord_id', 'landlord_id');
    }

    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class, 'lease_tenant', 'lease_id', 'tenant_id')->using(LeaseTenant::class)->withPivot('lease_tenant_id', 'is_primary_tenant', 'move_in_date', 'move_out_date')->withTimestamps();
    }

    public function paymentRecords(): HasMany
    {
        return $this->hasMany(PaymentRecord::class, 'lease_id', 'lease_id');
    }

    // Scopes

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeTerminated($query)
    {
        return $query->where('status', self::STATUS_TERMINATED);
    }

    public function scopeCurrent($query)
    {
        return $query
            ->where('status', self::STATUS_ACTIVE)
            ->where('start_date', '<=', now())
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            });
    }

    // Methods

    public function getPrimaryTenant(): ?Tenant
    {
        return $this->tenants()->wherePivot('is_primary_tenant', true)->first();
    }

    public function getNextDueDate(): Carbon
    {
        $now = now();
        $dueDay = (int) $this->payment_due_day;
        $dueDate = Carbon::createFromDate($now->year, $now->month, min($dueDay, 28));

        if ($dueDate->lessThanOrEqualTo($now)) {
            $dueDate = $dueDate->addMonth();
        }

        return $dueDate;
    }

    public function isOverdue(): bool
    {
        $latestPayment = $this->paymentRecords()->where('status', '!=', 'Paid')->orderBy('bills_due_date')->first();

        if (!$latestPayment) {
            return false;
        }

        return $latestPayment->bills_due_date->isPast();
    }

    // Accessors

    public function getRoomNumberAttribute(): string
    {
        return $this->room?->room_number ?? '';
    }

    public function getPrimaryTenantNameAttribute(): ?string
    {
        return $this->getPrimaryTenant()?->full_name;
    }
}
