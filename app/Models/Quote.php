<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'quote_number',
        'etablissement_id',
        'client_id',
        'project_id',
        'quote_date',
        'valid_until',
        'accepted_date',
        'rejected_date',
        'subtotal',
        'shipping_fees',
        'administration_fees',
        'discount_percentage',
        'discount_amount',
        'tax_total',
        'total',
        'taxes_breakdown',
        'status',
        'notes',
        'conditions',
        'metadata'
    ];

    protected $casts = [
        'quote_date' => 'date',
        'valid_until' => 'date',
        'accepted_date' => 'date',
        'rejected_date' => 'date',
        'subtotal' => 'decimal:2',
        'shipping_fees' => 'decimal:2',
        'administration_fees' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_total' => 'decimal:2',
        'total' => 'decimal:2',
        'taxes_breakdown' => 'array',
        'metadata' => 'array'
    ];

    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }

    public function client()
    {
        return $this->belongsTo(Customer::class, 'client_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function lines()
    {
        return $this->hasMany(QuoteLine::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function getIsExpiredAttribute()
    {
        return $this->valid_until < now();
    }

    public function getIsConvertibleAttribute()
    {
        return $this->status === 'accepte' && !$this->invoice;
    }

    public function accept()
    {
        $this->status = 'accepte';
        $this->accepted_date = now();
        $this->save();
    }

    public function reject($reason = null)
    {
        $this->status = 'refuse';
        $this->rejected_date = now();
        $this->notes = $reason;
        $this->save();
    }
}