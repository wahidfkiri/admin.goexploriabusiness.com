<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageRevision extends Model
{
    protected $fillable = [
        'menu_id',
        'content',
        'styles',
        'meta',
        'version',
        'user_id',
        'change_description'
    ];
    
    protected $casts = [
        'meta' => 'array'
    ];
    
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('d/m/Y H:i');
    }
    
    public function getUserNameAttribute(): string
    {
        return $this->user ? $this->user->name : 'Système';
    }
}
