<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class Template extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'html_content',
        'css_content',
        'thumbnail',
        'metadata',
        'user_id',
        'website_id',
        'categorie_id',
        'url',
        'js_content',
        'assets_folder',
        'assets_data'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'assets_data' => 'array'
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    /**
     * Accesseur pour le HTML formaté
     */
    public function getHtmlFormattedAttribute()
    {
        return htmlspecialchars($this->html_content);
    }

    /**
     * Accesseur pour le CSS formaté
     */
    public function getCssFormattedAttribute()
    {
        return htmlspecialchars($this->css_content);
    }

    /**
     * Scope pour les templates d'un utilisateur
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope pour la recherche
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
    }

    public function getAssetsFolderPathAttribute(): string
    {
        return 'templates/assets/' . $this->assets_folder;
    }
    
    /**
     * Get asset URL
     */
    public function getAssetUrl(string $path): string
    {
        return Storage::url('templates/assets/' . $this->assets_folder . '/' . $path);
    }

    /**
     * Get all assets
     */
    public function getAllAssetsAttribute(): array
    {
        $assets = [];
        $folder = 'templates/assets/' . $this->assets_folder;
        
        if (Storage::exists($folder)) {
            $files = Storage::allFiles($folder);
            
            foreach ($files as $file) {
                $assets[] = [
                    'path' => $file,
                    'url' => Storage::url($file),
                    'size' => Storage::size($file),
                    'type' => pathinfo($file, PATHINFO_EXTENSION)
                ];
            }
        }
        
        return $assets;
    }
    
    /**
     * Boot method to delete assets when template is deleted
     */
    protected static function boot()
    {
        parent::boot();
        
        static::deleting(function ($template) {
            if ($template->assets_folder) {
                Storage::deleteDirectory('templates/assets/' . $template->assets_folder);
            }
        });
    }
}