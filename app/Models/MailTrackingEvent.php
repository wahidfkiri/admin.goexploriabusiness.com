<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MailTrackingEvent extends Model
{
    protected $table = 'mail_tracking_events';
    
    protected $fillable = [
        'campaign_id',
        'subscriber_id',
        'event_type',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(MailCampaign::class);
    }

    public function subscriber(): BelongsTo
    {
        return $this->belongsTo(MailSubscriber::class);
    }
}