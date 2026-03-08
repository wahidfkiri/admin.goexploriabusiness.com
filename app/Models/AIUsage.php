<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AIUsage extends Model
{
    protected $table = 'ai_usages';
    
    protected $fillable = [
        'user_id',
        'model',
        'prompt_tokens',
        'completion_tokens',
        'total_tokens',
        'estimated_cost',
        'endpoint',
        'metadata'
    ];
    
    protected $casts = [
        'metadata' => 'array',
        'estimated_cost' => 'decimal:4'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }
    
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
    
    public function getCostFormattedAttribute()
    {
        return '$' . number_format($this->estimated_cost, 4);
    }
}