<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentRecord extends Model
{
    use HasFactory;

    protected $table = 'payment_record';
    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'lease_id', 
        'tenant_id',
        'rent_amount', 
        'electricity_amount', 
        'water_amount', 
        'other_fees', 
        'total_amount', 
        'amount_paid', 
        'balance', 
        'payment_method_id', 
        'payment_reference', 
        'status', 
        'bills_due_date', 
        'date_paid'
    ];

    protected $casts = [
        'rent_amount' => 'decimal:2',
        'electricity_amount' => 'decimal:2',
        'water_amount' => 'decimal:2',
        'other_fees' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance' => 'decimal:2',
        'bills_due_date' => 'date',
        'date_paid' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    const STATUS_UNPAID = 'Pending';
    const STATUS_PARTIAL = 'Partial';
    const STATUS_PAID = 'Paid';
    const STATUS_OVERDUE = 'Overdue';

    const METHOD_CASH = 'Cash';
    const METHOD_GCASH = 'GCash';

    // Relationships

    public function lease(): BelongsTo
    {
        return $this->belongsTo(Lease::class, 'lease_id', 'lease_id');
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'tenant_id');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'payment_method_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'payment_id', 'payment_id');
    }

    // Scopes

    public function scopeUnpaid($query)
    {
        return $query->where(function ($q) {
            $q->where('status', self::STATUS_UNPAID)->orWhere('status', self::STATUS_PARTIAL);
        });
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', self::STATUS_OVERDUE);
    }

    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    public function scopePendingPayment($query)
    {
        return $query->whereIn('status', [self::STATUS_UNPAID, self::STATUS_PARTIAL, self::STATUS_OVERDUE]);
    }

    // Methods

    public function markAsPaid(): void
    {
        $this->update([
            'status' => self::STATUS_PAID,
            'date_paid' => now(),
            'balance' => 0,
        ]);
    }

    public function calculateBalance(): float
    {
        return (float) ($this->total_amount - $this->amount_paid);
    }

    public function updateStatus(): void
    {
        if ($this->amount_paid >= $this->total_amount) {
            $this->status = self::STATUS_PAID;
        } elseif ($this->amount_paid > 0) {
            $this->status = self::STATUS_PARTIAL;
        } elseif ($this->bills_due_date->isPast()) {
            $this->status = self::STATUS_OVERDUE;
        } else {
            $this->status = self::STATUS_UNPAID;
        }

        $this->balance = $this->calculateBalance();
        $this->save();
    }

    // Accessors

    public function getTenantNameAttribute(): string
    {
        return $this->tenant?->full_name ?? '';
    }

    public function getRoomNumberAttribute(): string
    {
        return $this->lease?->room_number ?? '';
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->status === self::STATUS_OVERDUE || ($this->bills_due_date->isPast() && in_array($this->status, [self::STATUS_UNPAID, self::STATUS_PARTIAL]));
    }

    public function getDaysOverdueAttribute(): int
    {
        if (!$this->is_overdue) {
            return 0;
        }

        return (int) now()->diffInDays($this->bills_due_date);
    }
}
