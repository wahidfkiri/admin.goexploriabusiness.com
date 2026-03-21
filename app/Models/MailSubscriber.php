<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MailSubscriber extends Model
{
    protected $table = 'mail_subscribers';
    
    protected $fillable = [
        'etablissement_id',
        'email',
        'nom',
        'prenom',
        'is_subscribed',
        'unsubscribed_at',
    ];

    protected $casts = [
        'is_subscribed' => 'boolean',
        'unsubscribed_at' => 'datetime',
    ];

    public function etablissement(): BelongsTo
    {
        return $this->belongsTo(Etablissement::class);
    }

    /**
     * Relation avec les campagnes - Utilisation du modèle pivot personnalisé
     */
    public function campaigns(): BelongsToMany
    {
        return $this->belongsToMany(
            MailCampaign::class,                // Modèle lié
            'mail_campaign_subscriber',         // Table pivot
            'subscriber_id',                    // Clé étrangère du modèle actuel dans la pivot
            'campaign_id'                       // Clé étrangère du modèle lié dans la pivot
        )
        ->using(MailCampaignSubscriber::class)   // Utiliser le modèle pivot personnalisé
        ->withPivot('sent_at', 'opened_at', 'clicked_at', 'failed_at')
        ->withTimestamps();
    }

    public function trackingEvents()
    {
        return $this->hasMany(MailTrackingEvent::class, 'subscriber_id');
    }

    public function scopeSubscribed($query)
    {
        return $query->where('is_subscribed', true);
    }

    public function scopeByEtablissement($query, $etablissementId)
    {
        return $query->where('etablissement_id', $etablissementId);
    }
    
    /**
     * Récupère les statistiques d'engagement
     */
    public function getEngagementStatsAttribute()
    {
        $totalCampaigns = $this->campaigns()->count();
        $openedCount = $this->campaigns()->wherePivotNotNull('opened_at')->count();
        $clickedCount = $this->campaigns()->wherePivotNotNull('clicked_at')->count();
        
        return [
            'total_campaigns' => $totalCampaigns,
            'opened' => $openedCount,
            'clicked' => $clickedCount,
            'open_rate' => $totalCampaigns > 0 ? round(($openedCount / $totalCampaigns) * 100, 2) : 0,
            'click_rate' => $totalCampaigns > 0 ? round(($clickedCount / $totalCampaigns) * 100, 2) : 0,
        ];
    }
    
    /**
     * Couleur de l'avatar
     */
    public function getAvatarColorAttribute()
    {
        $hash = 0;
        for ($i = 0; $i < strlen($this->email); $i++) {
            $hash = ord($this->email[$i]) + (($hash << 5) - $hash);
        }
        
        $colors = [
            '#45b7d1', '#96ceb4', '#feca57', '#ff6b6b',
            '#9b59b6', '#3498db', '#1abc9c', '#e74c3c',
        ];
        
        return $colors[abs($hash) % count($colors)];
    }
    
    /**
     * Initiales de l'abonné
     */
    public function getInitialsAttribute()
    {
        $first = $this->prenom ? mb_substr($this->prenom, 0, 1) : '';
        $last = $this->nom ? mb_substr($this->nom, 0, 1) : '';
        return strtoupper($first . $last) ?: '?';
    }
}