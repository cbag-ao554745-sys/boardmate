<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $table = 'room';
    protected $primaryKey = 'room_id';

    protected $fillable = [
        'landlord_id',
        'room_number',
        'floor',
        'monthly_rent',
        'status',
    ];

    protected $casts = [
        'monthly_rent' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    const STATUS_AVAILABLE = 'Available';
    const STATUS_OCCUPIED = 'Occupied';
    const STATUS_MAINTENANCE = 'Under Maintenance';

    // Relationships

    public function landlord(): BelongsTo
    {
        return $this->belongsTo(Landlord::class, 'landlord_id');
    }

    public function leases(): HasMany
    {
        return $this->hasMany(Lease::class, 'room_id');
    }

    public function activeLease(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Lease::class, 'room_id')
            ->where('status', 'Active')
            ->latest('lease_id');
    }

    // Scopes

    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    public function scopeOccupied($query)
    {
        return $query->where('status', self::STATUS_OCCUPIED);
    }

    public function scopeUnderMaintenance($query)
    {
        return $query->where('status', self::STATUS_MAINTENANCE);
    }
}
