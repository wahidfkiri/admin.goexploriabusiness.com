<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'etablissement_id',
        'payment_id',
        'invoice_id',
        'client_id',
        'payment_gateway_id',
        'gateway_type',
        'amount',
        'currency',
        'status',
        'gateway_transaction_id',
        'gateway_payment_id',
        'gateway_status',
        'gateway_response',
        'subscription_id',
        'plan_id',
        'error_message',
        'error_details',
        'metadata',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
        'error_details' => 'array',
        'metadata' => 'array'
    ];

    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function client()
    {
        return $this->belongsTo(Customer::class, 'client_id');
    }

    public function gateway()
    {
        return $this->belongsTo(PaymentGateway::class, 'payment_gateway_id');
    }

    public function isSuccessful()
    {
        return $this->status === 'completed';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    public function isRefunded()
    {
        return $this->status === 'refunded';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            $transaction->transaction_id = 'TXN-' . strtoupper(uniqid());
        });
    }
}