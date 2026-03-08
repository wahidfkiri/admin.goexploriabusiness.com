<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'icon',
        'configuration',
        'is_active',
        'is_default',
        'order',
        'metadata'
    ];

    protected $casts = [
        'configuration' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'metadata' => 'array',
        'order' => 'integer'
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'method', 'code');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'payment_method', 'code');
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($method) {
            if ($method->is_default) {
                static::where('is_default', true)->update(['is_default' => false]);
            }
        });

        static::updating(function ($method) {
            if ($method->is_default && $method->isDirty('is_default')) {
                static::where('is_default', true)->update(['is_default' => false]);
            }
        });
    }
}