<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class PaymentGateway extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'etablissement_id',
        'name',
        'code',
        'type',
        'is_active',
        'is_default',
        'order',
        'config',
        'mode',
        'paypal_client_id',
        'paypal_client_secret',
        'paypal_webhook_id',
        'stripe_publishable_key',
        'stripe_secret_key',
        'stripe_webhook_secret',
        'supported_currencies',
        'fees',
        'description',
        'metadata'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'supported_currencies' => 'array',
        'fees' => 'array',
        'metadata' => 'array',
        'config' => 'array'
    ];

    // Encrypt sensitive data
    protected function setPaypalClientSecretAttribute($value)
    {
        $this->attributes['paypal_client_secret'] = $value ? Crypt::encryptString($value) : null;
    }

    protected function getPaypalClientSecretAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    protected function setStripeSecretKeyAttribute($value)
    {
        $this->attributes['stripe_secret_key'] = $value ? Crypt::encryptString($value) : null;
    }

    protected function getStripeSecretKeyAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }

    public function transactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function bankDetail()
    {
        return $this->belongsTo(BankDetail::class, 'bank_detail_id');
    }

    public function getConfigAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    public function isSandbox()
    {
        return $this->mode === 'sandbox';
    }

    public function isLive()
    {
        return $this->mode === 'live';
    }
}