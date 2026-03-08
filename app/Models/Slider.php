<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slider extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Spécifier la connexion de base de données
     * Commenter si vous utilisez la même DB que principale
     */
    // protected $connection = 'secondary';
    
    protected $table = 'sliders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'type',
        'image_path',
        'video_path',
        'video_type',
        'video_url',
        'thumbnail_path',
        'order',
        'is_active',
        'button_text',
        'button_url',
        'settings',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'settings' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'image_url',
        'video_embed_url',
        'thumbnail_url',
        'youtube_id',
        'is_youtube',
        'is_vimeo',
        'is_uploaded_video',
        'has_button',
    ];

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($slider) {
            if (empty($slider->order)) {
                $maxOrder = static::max('order') ?? 0;
                $slider->order = $maxOrder + 1;
            }
        });
    }

    /**
     * Scope for active sliders
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for inactive sliders
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope for image sliders
     */
    public function scopeImages($query)
    {
        return $query->where('type', 'image');
    }

    /**
     * Scope for video sliders
     */
    public function scopeVideos($query)
    {
        return $query->where('type', 'video');
    }

    /**
     * Order by order column
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Search sliders
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

    /**
     * Filter by type
     */
    public function scopeOfType($query, $type)
    {
        if ($type === 'image') {
            return $query->images();
        } elseif ($type === 'video') {
            return $query->videos();
        }
        
        return $query;
    }

    /**
     * Get the image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
                return $this->image_path;
            }
            return asset('storage/' . $this->image_path);
        }
        return asset('images/default-slider.jpg');
    }

    /**
     * Get the video embed URL (sans conflit)
     */
    public function getVideoEmbedUrlAttribute()
    {
        if ($this->type !== 'video') {
            return null;
        }

        if ($this->video_type === 'youtube' && $this->video_url) {
            return $this->extractYoutubeEmbedUrl($this->video_url);
        } elseif ($this->video_type === 'vimeo' && $this->video_url) {
            return $this->extractVimeoEmbedUrl($this->video_url);
        } elseif ($this->video_type === 'upload' && $this->video_path) {
            if (filter_var($this->video_path, FILTER_VALIDATE_URL)) {
                return $this->video_path;
            }
            return asset('storage/' . $this->video_path);
        }
        
        return null;
    }

    /**
     * Get the thumbnail URL
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail_path) {
            if (filter_var($this->thumbnail_path, FILTER_VALIDATE_URL)) {
                return $this->thumbnail_path;
            }
            return asset('storage/' . $this->thumbnail_path);
        } elseif ($this->image_path) {
            if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
                return $this->image_path;
            }
            return asset('storage/' . $this->image_path);
        }
        return asset('images/default-slider.jpg');
    }

    /**
     * Extract YouTube embed URL
     */
    private function extractYoutubeEmbedUrl($url)
    {
        if (!$url) return null;
        
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/';
        preg_match($pattern, $url, $matches);
        
        if (isset($matches[1])) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }
        
        return $url;
    }

    /**
     * Extract YouTube video ID
     */
    private function extractYoutubeId($url)
    {
        if (!$url) return null;
        
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/';
        preg_match($pattern, $url, $matches);
        return $matches[1] ?? null;
    }

    /**
     * Extract Vimeo embed URL
     */
    private function extractVimeoEmbedUrl($url)
    {
        if (!$url) return null;
        
        $pattern = '/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:$|\/|\?)/';
        preg_match($pattern, $url, $matches);
        
        if (isset($matches[3])) {
            return 'https://player.vimeo.com/video/' . $matches[3];
        }
        
        return $url;
    }

    /**
     * Get YouTube video ID
     */
    public function getYoutubeIdAttribute()
    {
        if ($this->video_type === 'youtube' && $this->video_url) {
            return $this->extractYoutubeId($this->video_url);
        }
        return null;
    }

    /**
     * Check if slider is YouTube video
     */
    public function getIsYoutubeAttribute()
    {
        return $this->video_type === 'youtube';
    }

    /**
     * Check if slider is Vimeo video
     */
    public function getIsVimeoAttribute()
    {
        return $this->video_type === 'vimeo';
    }

    /**
     * Check if slider is uploaded video
     */
    public function getIsUploadedVideoAttribute()
    {
        return $this->video_type === 'upload';
    }

    /**
     * Check if slider has button
     */
    public function getHasButtonAttribute()
    {
        return !empty($this->button_text) && !empty($this->button_url);
    }

    /**
     * Get original video URL (safe getter)
     */
    public function getVideoUrl()
    {
        return $this->attributes['video_url'] ?? null;
    }

    /**
     * Get original video path (safe getter)
     */
    public function getVideoPath()
    {
        return $this->attributes['video_path'] ?? null;
    }
}