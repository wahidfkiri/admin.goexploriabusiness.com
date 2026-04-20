<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Abonnement extends Model
{
    use HasFactory;

    protected $table = 'abonnements';

    protected $fillable = [
        'etablissement_id',
        'plan_id',
        'reference',
        'start_date',
        'end_date',
        'amount_paid',
        'currency',
        'status',
        'payment_status',
        'auto_renew',
        'cancelled_at',
        'cancellation_reason'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'cancelled_at' => 'datetime',
        'amount_paid' => 'decimal:2',
        'auto_renew' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($abonnement) {
            if (empty($abonnement->reference)) {
                $abonnement->reference = 'SUB-' . strtoupper(Str::random(13));
            }
        });
    }

    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                     ->where('end_date', '>=', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired')
                     ->orWhere('end_date', '<', now());
    }

    public function scopeForEtablissement($query, $etablissementId)
    {
        return $query->where('etablissement_id', $etablissementId);
    }

    public function isActive()
    {
        return $this->status === 'active' && $this->end_date >= now();
    }

    public function isExpired()
    {
        return $this->end_date < now() || $this->status === 'expired';
    }

    public function daysRemaining()
    {
        if ($this->isExpired()) {
            return 0;
        }
        return now()->diffInDays($this->end_date);
    }

    public function cancel($reason = null)
    {
        $this->status = 'cancelled';
        $this->cancelled_at = now();
        $this->cancellation_reason = $reason;
        $this->save();
        
        // Mettre à jour l'établissement
        $this->etablissement->update([
            'subscription_status' => 'expired',
            'current_abonnement_id' => null
        ]);
    }
}