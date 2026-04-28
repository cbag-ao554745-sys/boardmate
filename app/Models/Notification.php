<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notification';
    protected $primaryKey = 'notification_id';

    protected $fillable = [
        'payment_id', 
        'landlord_id', 
        'type', 
        'message', 
        'sent_at', 
        'is_read'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    const TYPE_DUE_SOON = 'Due Soon';
    const TYPE_OVERDUE = 'Overdue';
    const TYPE_PAYMENT_RECEIVED = 'Payment Received';
    const TYPE_SYSTEM = 'System';

    // Relationships

    public function paymentRecord(): BelongsTo
    {
        return $this->belongsTo(PaymentRecord::class, 'payment_id', 'payment_id');
    }

    public function landlord(): BelongsTo
    {
        return $this->belongsTo(Landlord::class, 'landlord_id', 'landlord_id');
    }

    // Scopes

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    // Methods

    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }

    public function markAsUnread(): void
    {
        $this->update(['is_read' => false]);
    }

    // Accessors

    public function getTenantNameAttribute(): ?string
    {
        return $this->paymentRecord?->tenant_name;
    }

    public function getRoomNumberAttribute(): ?string
    {
        return $this->paymentRecord?->room_number;
    }
}
