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

    public function campaigns(): BelongsToMany
    {
        return $this->belongsToMany(MailCampaign::class, 'mail_campaign_subscriber')
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
}