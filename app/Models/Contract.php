<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'contract_number',
        'etablissement_id',
        'client_id',
        'project_id',
        'product_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'status',
        'type',
        'amount',
        'billing_frequency',
        'auto_renew',
        'renewal_notice_days',
        'terms',
        'special_conditions',
        'signed_document',
        'signed_at',
        'cancelled_at',
        'cancellation_reason',
        'metadata'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'signed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'amount' => 'decimal:2',
        'auto_renew' => 'boolean',
        'signed_document' => 'array',
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

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function renewals()
    {
        return $this->hasMany(Renewal::class);
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'actif';
    }

    public function getIsExpiringSoonAttribute()
    {
        if (!$this->end_date) return false;
        return $this->end_date->diffInDays(now()) <= $this->renewal_notice_days;
    }

    public function renew()
    {
        $renewal = $this->renewals()->create([
            'renewal_date' => now(),
            'amount' => $this->amount,
            'status' => 'en_attente'
        ]);

        // Créer une facture si nécessaire
        if ($this->billing_frequency !== 'unique') {
            // Logique de création de facture
        }

        return $renewal;
    }

    public function cancel($reason = null)
    {
        $this->status = 'resilie';
        $this->cancelled_at = now();
        $this->cancellation_reason = $reason;
        $this->save();
    }
}