<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tenant extends Model
{
    use HasFactory;

    protected $table = 'tenant';
    protected $primaryKey = 'tenant_id';

    protected $fillable = [
        'person_id', 
        'guardian_person_id', 
        'landlord_id', 
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    const STATUS_ACTIVE = 'Active';
    const STATUS_INACTIVE = 'Inactive';
    const STATUS_BLACKLISTED = 'Blacklisted';

    // Relationships

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person_id', 'person_id');
    }

    public function guardian(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'guardian_person_id', 'person_id');
    }

    public function landlord(): BelongsTo
    {
        return $this->belongsTo(Landlord::class, 'landlord_id', 'landlord_id');
    }

    public function leases(): BelongsToMany
    {
        return $this->belongsToMany(Lease::class, 'lease_tenant', 'tenant_id', 'lease_id')->using(LeaseTenant::class)->withPivot('lease_tenant_id', 'is_primary_tenant', 'move_in_date', 'move_out_date')->withTimestamps();
    }

    public function paymentRecords(): HasMany
    {
        return $this->hasMany(PaymentRecord::class, 'tenant_id', 'tenant_id');
    }

    // Scopes

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    public function scopeBlacklisted($query)
    {
        return $query->where('status', self::STATUS_BLACKLISTED);
    }

    // Accessors

    public function getFullNameAttribute(): string
    {
        return $this->person?->full_name ?? '';
    }

    public function getContactNumberAttribute(): string
    {
        return $this->person?->contact_number ?? '';
    }

    public function getAddressAttribute(): string
    {
        return $this->person?->address ?? '';
    }

    public function getGuardianNameAttribute(): ?string
    {
        return $this->guardian?->full_name;
    }

    public function getGuardianContactAttribute(): ?string
    {
        return $this->guardian?->contact_number;
    }
}
