<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Slider extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sliders';

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
        'country_id',
        'province_id',
        'region_id',
        'ville_id',
        'location_path',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'settings' => 'array',
    ];

    protected $appends = [
        'image_url',
        'video_embed_url',
        'thumbnail_url',
        'youtube_id',
        'is_youtube',
        'is_vimeo',
        'is_uploaded_video',
        'has_button',
        'is_cdn_image',
        'is_cdn_video',
        'full_location',
        'location_hierarchy',
    ];

    // Relations
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function ville()
    {
        return $this->belongsTo(Ville::class);
    }

    // Accessor pour la localisation complète
    public function getFullLocationAttribute()
    {
        $parts = [];
        if ($this->country) $parts[] = $this->country->name;
        if ($this->province) $parts[] = $this->province->name;
        if ($this->region) $parts[] = $this->region->name;
        if ($this->ville) $parts[] = $this->ville->name;
        return implode(' › ', $parts);
    }

    // Accessor pour la hiérarchie complète
    public function getLocationHierarchyAttribute()
    {
        return [
            'country_id' => $this->country_id,
            'country_name' => $this->country?->name,
            'province_id' => $this->province_id,
            'province_name' => $this->province?->name,
            'region_id' => $this->region_id,
            'region_name' => $this->region?->name,
            'ville_id' => $this->ville_id,
            'ville_name' => $this->ville?->name,
            'full_path' => $this->full_location,
        ];
    }

    // Scope pour rechercher par mot-clé dans toute la hiérarchie
    public function scopeSearchLocation($query, $keyword)
    {
        if (empty($keyword)) return $query;

        return $query->where(function ($q) use ($keyword) {
            $q->whereHas('country', function ($sub) use ($keyword) {
                $sub->where('name', 'LIKE', "%{$keyword}%");
            })->orWhereHas('province', function ($sub) use ($keyword) {
                $sub->where('name', 'LIKE', "%{$keyword}%");
            })->orWhereHas('region', function ($sub) use ($keyword) {
                $sub->where('name', 'LIKE', "%{$keyword}%");
            })->orWhereHas('ville', function ($sub) use ($keyword) {
                $sub->where('name', 'LIKE', "%{$keyword}%");
            })->orWhere('location_path', 'LIKE', "%{$keyword}%");
        });
    }

    // Générer le chemin hiérarchique avant sauvegarde
    protected static function booted()
    {
        parent::booted();

        static::creating(function ($slider) {
            if (empty($slider->order)) {
                $maxOrder = static::max('order') ?? 0;
                $slider->order = $maxOrder + 1;
            }
            $slider->location_path = $slider->generateLocationPath();
        });

        static::updating(function ($slider) {
            $slider->location_path = $slider->generateLocationPath();
        });
    }

    protected function generateLocationPath()
    {
        $parts = [];
        if ($this->country) $parts[] = $this->country->name;
        if ($this->province) $parts[] = $this->province->name;
        if ($this->region) $parts[] = $this->region->name;
        if ($this->ville) $parts[] = $this->ville->name;
        return implode(' › ', $parts);
    }

    // Reste du code existant (scopes, méthodes, etc.)
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeImages($query)
    {
        return $query->where('type', 'image');
    }

    public function scopeVideos($query)
    {
        return $query->where('type', 'video');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
    }

    public function scopeStatus($query, $status)
    {
        if ($status === 'active') {
            return $query->active();
        } elseif ($status === 'inactive') {
            return $query->inactive();
        }
        return $query;
    }

    public function scopeOfType($query, $type)
    {
        if ($type === 'image') {
            return $query->images();
        } elseif ($type === 'video') {
            return $query->videos();
        }
        return $query;
    }

    private function isCdnUrl($path)
    {
        if (!$path) return false;
        $cdnUrl = env('CDN_URL', 'https://upload.goexploriabusiness.com');
        return Str::startsWith($path, $cdnUrl);
    }

    public function getIsCdnImageAttribute()
    {
        return $this->isCdnUrl($this->image_path);
    }

    public function getIsCdnVideoAttribute()
    {
        return $this->isCdnUrl($this->video_path);
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image_path) {
            return asset('images/default-slider.jpg');
        }
        if ($this->isCdnUrl($this->image_path)) {
            return $this->image_path;
        }
        if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
            return $this->image_path;
        }
        return asset('storage/' . $this->image_path);
    }

    public function getVideoEmbedUrlAttribute()
    {
        if ($this->type !== 'video') return null;

        if ($this->video_type === 'youtube' && $this->video_url) {
            return $this->extractYoutubeEmbedUrl($this->video_url);
        } elseif ($this->video_type === 'vimeo' && $this->video_url) {
            return $this->extractVimeoEmbedUrl($this->video_url);
        } elseif ($this->video_type === 'upload' && $this->video_path) {
            if ($this->isCdnUrl($this->video_path)) return $this->video_path;
            if (filter_var($this->video_path, FILTER_VALIDATE_URL)) return $this->video_path;
            return asset('storage/' . $this->video_path);
        }
        return null;
    }

    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail_path) {
            if ($this->isCdnUrl($this->thumbnail_path)) return $this->thumbnail_path;
            if (filter_var($this->thumbnail_path, FILTER_VALIDATE_URL)) return $this->thumbnail_path;
            return asset('storage/' . $this->thumbnail_path);
        } elseif ($this->image_path) {
            return $this->image_url;
        }
        return asset('images/default-slider.jpg');
    }

    private function extractYoutubeEmbedUrl($url)
    {
        if (!$url) return null;
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/';
        preg_match($pattern, $url, $matches);
        return isset($matches[1]) ? 'https://www.youtube.com/embed/' . $matches[1] : $url;
    }

    private function extractYoutubeId($url)
    {
        if (!$url) return null;
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/';
        preg_match($pattern, $url, $matches);
        return $matches[1] ?? null;
    }

    private function extractVimeoEmbedUrl($url)
    {
        if (!$url) return null;
        $pattern = '/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:$|\/|\?)/';
        preg_match($pattern, $url, $matches);
        return isset($matches[3]) ? 'https://player.vimeo.com/video/' . $matches[3] : $url;
    }

    public function getYoutubeIdAttribute()
    {
        if ($this->video_type === 'youtube' && $this->video_url) {
            return $this->extractYoutubeId($this->video_url);
        }
        return null;
    }

    public function getIsYoutubeAttribute()
    {
        return $this->video_type === 'youtube';
    }

    public function getIsVimeoAttribute()
    {
        return $this->video_type === 'vimeo';
    }

    public function getIsUploadedVideoAttribute()
    {
        return $this->video_type === 'upload';
    }

    public function getHasButtonAttribute()
    {
        return !empty($this->button_text) && !empty($this->button_url);
    }

    public function getVideoUrl()
    {
        return $this->attributes['video_url'] ?? null;
    }

    public function getVideoPath()
    {
        return $this->attributes['video_path'] ?? null;
    }

    public function getStoragePath($attribute)
    {
        $path = $this->$attribute;
        if (!$path || $this->isCdnUrl($path) || filter_var($path, FILTER_VALIDATE_URL)) {
            return null;
        }
        return storage_path('app/public/' . $path);
    }

    public function fileExists($attribute)
    {
        $path = $this->$attribute;
        if (!$path) return false;
        if ($this->isCdnUrl($path)) return true;
        if (filter_var($path, FILTER_VALIDATE_URL)) return true;
        return Storage::disk('public')->exists($path);
    }

    public function deleteFiles()
    {
        $files = ['image_path', 'video_path', 'thumbnail_path'];
        foreach ($files as $file) {
            $path = $this->$file;
            if ($path && !$this->isCdnUrl($path) && !filter_var($path, FILTER_VALIDATE_URL)) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }
    }

    public function delete()
    {
        if ($this->forceDeleting) {
            $this->deleteFiles();
        }
        return parent::delete();
    }
}