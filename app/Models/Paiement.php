<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Paiement extends Model
{
    use HasFactory;

    protected $table = 'paiements';

    protected $fillable = [
        'etablissement_id',
        'abonnement_id',
        'transaction_id',
        'reference',
        'amount',
        'currency',
        'payment_method',
        'status',
        'payment_details',
        'paid_at',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_details' => 'array',
        'paid_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($paiement) {
            if (empty($paiement->transaction_id)) {
                $paiement->transaction_id = 'TXN-' . strtoupper(Str::random(15));
            }
            if (empty($paiement->reference)) {
                $paiement->reference = 'PAY-' . strtoupper(Str::random(12));
            }
        });
    }

    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }

    public function abonnement()
    {
        return $this->belongsTo(Abonnement::class);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function markAsCompleted()
    {
        $this->status = 'completed';
        $this->paid_at = now();
        $this->save();
        
        // Mettre à jour le statut de paiement de l'abonnement
        $this->abonnement->update(['payment_status' => 'paid']);
    }
}