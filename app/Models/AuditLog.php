<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_log';
    protected $primaryKey = 'log_id';

    public $timestamps = false;

    protected $fillable = [
        'landlord_id',
        'action',
        'table_name',
        'record_id',
        'description',
        'timestamp',
    ];

    protected $casts = [
        'record_id' => 'integer',
        'timestamp' => 'datetime',
    ];

    const ACTION_INSERT  = 'INSERT';
    const ACTION_UPDATE  = 'UPDATE';
    const ACTION_DELETE  = 'DELETE';
    const ACTION_LOGIN   = 'LOGIN';
    const ACTION_LOGOUT  = 'LOGOUT';

    public function landlord(): BelongsTo
    {
        return $this->belongsTo(Landlord::class, 'landlord_id', 'landlord_id');
    }

    public function scopeAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('timestamp', now());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereYear('timestamp', now()->year)
            ->whereMonth('timestamp', now()->month);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('timestamp', now()->year);
    }

    public function getLandlordNameAttribute(): string
    {
        return $this->landlord?->full_name ?? '';
    }
}