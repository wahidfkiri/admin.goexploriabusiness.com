<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MailCampaign extends Model
{
    protected $table = 'mail_campaigns';
    
    protected $fillable = [
        'nom',
        'sujet',
        'contenu',
        'options',
        'status',
        'scheduled_at',
        'sent_at',
        'created_by',
    ];

    protected $casts = [
        'options' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(MailSubscriber::class, 'mail_campaign_subscriber')
                    ->withPivot('sent_at', 'opened_at', 'clicked_at', 'failed_at')
                    ->withTimestamps();
    }

    public function trackingEvents()
    {
        return $this->hasMany(MailTrackingEvent::class, 'campaign_id');
    }

    public function missions()
    {
        return $this->hasMany(Mission::class, 'campagne_id');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function getStatsAttribute()
    {
        return [
            'total' => $this->subscribers()->count(),
            'sent' => $this->subscribers()->wherePivotNotNull('sent_at')->count(),
            'opened' => $this->subscribers()->wherePivotNotNull('opened_at')->count(),
            'clicked' => $this->subscribers()->wherePivotNotNull('clicked_at')->count(),
            'failed' => $this->subscribers()->wherePivotNotNull('failed_at')->count(),
        ];
    }
}