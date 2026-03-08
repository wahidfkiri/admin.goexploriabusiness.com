<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectStatus extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'color',
        'icon',
        'description',
        'order',
        'is_active',
        'is_default',
        'metadata'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'metadata' => 'array'
    ];

    public function projects()
    {
        return $this->hasMany(Project::class, 'status', 'code');
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($status) {
            if ($status->is_default) {
                static::where('is_default', true)->update(['is_default' => false]);
            }
        });
    }
}