<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'payment_methods';
    protected $primaryKey = 'payment_method_id';

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'requires_reference',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'requires_reference' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        /**
         * Automatically generate the code from the name when creating or updating.
         */
        static::creating(function (self $model) {
            if (empty($model->code)) {
                $model->code = Str::slug($model->name, '-');
            }
        });

        static::updating(function (self $model) {
            // Allow code to be regenerated if name changes, but only if code is empty
            if (empty($model->code)) {
                $model->code = Str::slug($model->name, '-');
            }
        });
    }

    /**
     * Scope to get only active payment methods.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
