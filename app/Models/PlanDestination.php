<?php
// app/Models/PlanDestination.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class PlanDestination extends Model
{
    use HasFactory;

    protected $table = 'plan_destination';

    protected $fillable = [
        'plan_id',
        'destination_name',
        'destination_slug',
        'destination_image',
        'destination_description',
        'destination_country',
        'destination_city',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // =====================
    // BOOT
    // =====================
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($destination) {
            if (empty($destination->destination_slug)) {
                $destination->destination_slug = Str::slug($destination->destination_name);
            }
        });
    }

    // =====================
    // RELATIONS
    // =====================

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    // =====================
    // ACCESSORS
    // =====================

    public function getImageUrlAttribute(): ?string
    {
        if ($this->destination_image && filter_var($this->destination_image, FILTER_VALIDATE_URL)) {
            return $this->destination_image;
        }
        if ($this->destination_image) {
            return asset('storage/' . ltrim($this->destination_image, '/storage/'));
        }
        return null;
    }

    public function getFullLocationAttribute(): string
    {
        $parts = array_filter([$this->destination_city, $this->destination_country]);
        return implode(', ', $parts);
    }
}