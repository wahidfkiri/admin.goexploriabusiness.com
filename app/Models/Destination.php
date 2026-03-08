<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Destination extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'image',
        'description',
        'is_active',
        'slug',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Automatically generate slug from name
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($destination) {
            $destination->slug = $destination->slug ?? 
                \Illuminate\Support\Str::slug($destination->name);
        });

        static::updating(function ($destination) {
            if ($destination->isDirty('name')) {
                $destination->slug = \Illuminate\Support\Str::slug($destination->name);
            }
        });
    }

    /**
     * Scope for active destinations
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for inactive destinations
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Search destinations
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
    }

    /**
     * Filter by status
     */
    public function scopeStatus($query, $status)
    {
        if ($status === 'active') {
            return $query->active();
        } elseif ($status === 'inactive') {
            return $query->inactive();
        }
        
        return $query;
    }
}