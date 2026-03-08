<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Renewal extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'invoice_id',
        'renewal_date',
        'amount',
        'status',
        'notes',
        'metadata'
    ];

    protected $casts = [
        'renewal_date' => 'date',
        'amount' => 'decimal:2',
        'metadata' => 'array'
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}