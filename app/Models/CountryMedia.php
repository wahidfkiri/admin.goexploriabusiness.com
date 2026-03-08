<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CountryMedia extends Model
{
    use SoftDeletes;

    protected $table = 'country_medias';

    protected $fillable = [
        'title',
        'description',
        'type',
        'image_path',
        'video_path',
        'video_url',
        'video_id',
        'video_provider',
        'duration',
        'mime_type',
        'file_size',
        'width',
        'height',
        'alt_text',
        'tags',
        'sort_order',
        'is_featured',
        'is_active',
        'country_id',
        'activity_id',
        'created_by'
    ];

    protected $casts = [
        'tags' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'duration' => 'integer',
        'file_size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'sort_order' => 'integer'
    ];

    /**
     * Relation avec le pays
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Relation avec l'activité
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * Relation avec l'utilisateur créateur
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the URL for the image
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) {
            return $this->getVideoThumbnailUrl();
        }

        if (Str::startsWith($this->image_path, ['http://', 'https://'])) {
            return $this->image_path;
        }

        return Storage::disk('public')->exists($this->image_path) 
            ? Storage::disk('public')->url($this->image_path)
            : null;
    }

    /**
     * Get the URL for the video
     */
    public function getVideoUrlAttribute(): ?string
    {
        if ($this->type === 'video_local' && $this->video_path) {
            return Storage::disk('public')->exists($this->video_path) 
                ? Storage::disk('public')->url($this->video_path)
                : null;
        }

        return $this->attributes['video_url'];
    }

    /**
     * Get thumbnail URL for videos
     */
    public function getVideoThumbnailUrl(): ?string
    {
        if ($this->type === 'image' || $this->image_path) {
            return $this->getImageUrlAttribute();
        }

        // Générer des thumbnails pour les vidéos externes
        if ($this->type === 'video_youtube' && $this->video_id) {
            return "https://img.youtube.com/vi/{$this->video_id}/hqdefault.jpg";
        }

        if ($this->type === 'video_vimeo' && $this->video_id) {
            // Pour Vimeo, on aurait besoin d'une API call
            return null;
        }

        return null;
    }

    /**
     * Get embed URL for videos
     */
    public function getEmbedUrlAttribute(): ?string
    {
        if ($this->type === 'video_youtube' && $this->video_id) {
            return "https://www.youtube.com/embed/{$this->video_id}";
        }

        if ($this->type === 'video_vimeo' && $this->video_id) {
            return "https://player.vimeo.com/video/{$this->video_id}";
        }

        if ($this->type === 'video_local') {
            return $this->getVideoUrlAttribute();
        }

        return null;
    }

    /**
     * Check if media is a video
     */
    public function getIsVideoAttribute(): bool
    {
        return Str::startsWith($this->type, 'video_');
    }

    /**
     * Check if media is an image
     */
    public function getIsImageAttribute(): bool
    {
        return $this->type === 'image';
    }

    /**
     * Get file size in human readable format
     */
    public function getFileSizeFormattedAttribute(): string
    {
        if (!$this->file_size) {
            return 'N/A';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unitIndex = 0;

        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }

        return round($size, 2) . ' ' . $units[$unitIndex];
    }

    /**
     * Get duration in human readable format
     */
    public function getDurationFormattedAttribute(): string
    {
        if (!$this->duration) {
            return 'N/A';
        }

        $hours = floor($this->duration / 3600);
        $minutes = floor(($this->duration % 3600) / 60);
        $seconds = $this->duration % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    /**
     * Scope for active media
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for featured media
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for images only
     */
    public function scopeImages($query)
    {
        return $query->where('type', 'image');
    }

    /**
     * Scope for videos only
     */
    public function scopeVideos($query)
    {
        return $query->where('type', 'like', 'video_%');
    }

    /**
     * Scope for specific video type
     */
    public function scopeVideoType($query, $type)
    {
        return $query->where('type', 'video_' . $type);
    }

    /**
     * Scope for country
     */
    public function scopeForCountry($query, $countryId)
    {
        return $query->where('country_id', $countryId);
    }

    /**
     * Scope for activity
     */
    public function scopeForActivity($query, $activityId)
    {
        return $query->where('activity_id', $activityId);
    }

    /**
     * Boot method for generating thumbnails
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($media) {
            if (empty($media->created_by) && auth()->check()) {
                $media->created_by = auth()->id();
            }
        });
    }
}