<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MailCampaignSubscriber extends Pivot
{
    protected $table = 'mail_campaign_subscriber';
    
    protected $casts = [
        'sent_at' => 'datetime',
        'opened_at' => 'datetime',
        'clicked_at' => 'datetime',
        'failed_at' => 'datetime',
    ];
}