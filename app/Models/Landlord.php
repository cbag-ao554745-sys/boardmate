<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Landlord extends Model
{
    use HasFactory;

    protected $table = 'landlord';
    protected $primaryKey = 'landlord_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'person_id', 
        'user_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class, 'landlord_id');
    }

    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class, 'landlord_id');
    }

    public function leases(): HasMany
    {
        return $this->hasMany(Lease::class, 'landlord_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'landlord_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'landlord_id');
    }

    // Accessors

    /**
     * Get the full name of the landlord
     */
    public function getFullNameAttribute(): string
    {
        if ($this->person) {
            return trim($this->person->first_name . ' ' . $this->person->last_name);
        }
        return $this->user?->username ?? 'Unknown';
    }

    /**
     * Get the email address
     */
    public function getEmailAttribute(): string
    {
        return $this->user?->email ?? '';
    }

    /**
     * Get the contact number from person
     */
    public function getContactNumberAttribute(): string
    {
        return $this->person?->contact_number ?? '';
    }

    /**
     * Get the full address from person
     */
    public function getAddressAttribute(): string
    {
        if (!$this->person) {
            return '';
        }
        $address = array_filter([$this->person->address_line_1, $this->person->address_line_2, $this->person->city, $this->person->province, $this->person->postal_code]);
        return implode(', ', $address);
    }
}
