<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class LeaseTenant extends Pivot
{
    use HasFactory;

    protected $table = 'lease_tenant';

    protected $primaryKey = 'lease_tenant_id';
    public $incrementing = true;

    public $timestamps = true;

    protected $fillable = [
      'lease_id', 
      'tenant_id', 
      'is_primary_tenant',
      'move_in_date', 
      'move_out_date'
    ];

    protected $casts = [
        'is_primary_tenant' => 'boolean',
        'move_in_date' => 'date',
        'move_out_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
