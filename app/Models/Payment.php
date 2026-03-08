<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'payment_reference',
        'etablissement_id',
        'invoice_id',
        'client_id',
        'payment_date',
        'amount',
        'method',
        'transaction_id',
        'check_number',
        'bank_name',
        'status',
        'notes',
        'metadata',
        'received_by'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
        'metadata' => 'array'
    ];

    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function client()
    {
        return $this->belongsTo(Customer::class, 'client_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($payment) {
            $payment->payment_reference = 'PAY-' . strtoupper(uniqid());
        });

        static::saved(function ($payment) {
            if ($payment->invoice) {
                $payment->invoice->updatePaidAmount();
            }
        });
    }
}