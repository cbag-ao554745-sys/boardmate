<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Person extends Model
{
    use HasFactory;

    protected $table = 'person';
    protected $primaryKey = 'person_id';

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'date_of_birth',
        'gender',
        'contact_number',
        'address_line_1',
        'address_line_2',
        'city',
        'province',
        'postal_code',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships

    public function landlord(): HasOne
    {
        return $this->hasOne(Landlord::class, 'person_id');
    }

    public function tenant(): HasOne
    {
        return $this->hasOne(Tenant::class, 'person_id');
    }

    public function guardiansFor(): HasMany
    {
        return $this->hasMany(Tenant::class, 'guardian_person_id');
    }

    // Accessors

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }

    public function getAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address_line_1,
            $this->address_line_2,
            $this->city,
            $this->province,
            $this->postal_code,
        ]);

        return implode(', ', $parts);
    }
}
