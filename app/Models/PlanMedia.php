<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlanMedia extends Model
{
    use HasFactory;

    protected $table = 'plan_media';

    protected $fillable = [
        'plan_id',
        'type',
        'file_url',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
        'video_platform',
        'thumbnail_url',
        'thumbnail_path',
        'is_primary',
        'sort_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'file_size'  => 'integer',
        'sort_order' => 'integer',
    ];

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

    public function getIsImageAttribute(): bool
    {
        return $this->type === 'image';
    }

    public function getIsVideoAttribute(): bool
    {
        return $this->type === 'video';
    }

    public function getIsYoutubeAttribute(): bool
    {
        return $this->video_platform === 'youtube';
    }

    public function getIsVimeoAttribute(): bool
    {
        return $this->video_platform === 'vimeo';
    }

    public function getIsUploadedVideoAttribute(): bool
    {
        return $this->video_platform === 'upload';
    }

    public function getFileSizeFormattedAttribute(): string
    {
        if (!$this->file_size) return '—';
        $units = ['B', 'KB', 'MB', 'GB'];
        $size  = $this->file_size;
        $i     = 0;
        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }
        return round($size, 2) . ' ' . $units[$i];
    }

    /**
     * YouTube embed URL derived from regular URL
     */
    public function getYoutubeIdAttribute(): ?string
    {
        if (!$this->is_youtube) return null;
        preg_match(
            '/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/',
            $this->file_url,
            $m
        );
        return $m[1] ?? null;
    }

    public function getEmbedUrlAttribute(): ?string
    {
        if ($this->is_youtube && $this->youtube_id) {
            return "https://www.youtube.com/embed/{$this->youtube_id}";
        }
        if ($this->is_vimeo) {
            $id = preg_replace('/[^0-9]/', '', $this->file_url);
            return "https://player.vimeo.com/video/{$id}";
        }
        return $this->file_url;
    }
}