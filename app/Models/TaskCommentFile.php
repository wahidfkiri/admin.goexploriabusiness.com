<?php
// app/Models/TaskCommentFile.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class TaskCommentFile extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'task_comment_id',
        'filename',
        'original_filename',
        'filepath',
        'mime_type',
        'size',
        'disk',
        'metadata',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'json',
        'size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Les attributs qui doivent être cachés pour les tableaux.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'filepath',
    ];

    /**
     * Relations
     */
    public function taskComment(): BelongsTo
    {
        return $this->belongsTo(TaskComment::class);
    }

    /**
     * Accesseurs
     */
    public function getUrlAttribute(): string
    {
        return Storage::disk($this->disk)->url($this->filepath);
    }

    public function getFullPathAttribute(): string
    {
        return Storage::disk($this->disk)->path($this->filepath);
    }

    public function getFileSizeAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getIsImageAttribute(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function getIsPdfAttribute(): bool
    {
        return $this->mime_type === 'application/pdf';
    }

    public function getIsDocumentAttribute(): bool
    {
        return in_array($this->mime_type, [
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain',
        ]);
    }

    /**
     * NOUVELLE MÉTHODE: Obtenir l'icône correspondant au type de fichier
     */
    public function getFileIcon(): string
    {
        if ($this->is_image) {
            return 'fa-image';
        }
        
        if ($this->is_pdf) {
            return 'fa-file-pdf';
        }
        
        if ($this->is_document) {
            // Déterminer le type spécifique de document
            if (str_contains($this->mime_type, 'word') || str_contains($this->mime_type, 'document')) {
                return 'fa-file-word';
            }
            if (str_contains($this->mime_type, 'sheet') || str_contains($this->mime_type, 'excel')) {
                return 'fa-file-excel';
            }
            if (str_contains($this->mime_type, 'presentation') || str_contains($this->mime_type, 'powerpoint')) {
                return 'fa-file-powerpoint';
            }
            if (str_contains($this->mime_type, 'text/plain')) {
                return 'fa-file-alt';
            }
            return 'fa-file';
        }

        // Autres types
        if (str_contains($this->mime_type, 'zip') || str_contains($this->mime_type, 'compressed')) {
            return 'fa-file-archive';
        }
        
        if (str_contains($this->mime_type, 'audio')) {
            return 'fa-file-audio';
        }
        
        if (str_contains($this->mime_type, 'video')) {
            return 'fa-file-video';
        }

        return 'fa-file';
    }

    /**
     * Obtenir la classe CSS de l'icône (pour la couleur)
     */
    public function getIconClass(): string
    {
        if ($this->is_image) {
            return 'image';
        }
        
        if ($this->is_pdf) {
            return 'pdf';
        }
        
        if ($this->is_document) {
            if (str_contains($this->mime_type, 'word')) {
                return 'word';
            }
            if (str_contains($this->mime_type, 'excel')) {
                return 'excel';
            }
            if (str_contains($this->mime_type, 'powerpoint')) {
                return 'powerpoint';
            }
            return 'document';
        }

        if (str_contains($this->mime_type, 'zip') || str_contains($this->mime_type, 'compressed')) {
            return 'archive';
        }

        return 'other';
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->is_image) {
            // Vous pouvez créer une version thumbnail de l'image
            return $this->url;
        }

        // Retourner une icône par défaut selon le type
        return asset('images/file-icons/' . $this->getFileIcon() . '.png');
    }

    /**
     * Mutateurs
     */
    public function setFilenameAttribute($value)
    {
        $this->attributes['filename'] = $value;
        $this->attributes['original_filename'] = $value;
    }

    /**
     * Méthodes utilitaires
     */
    public function deleteFile(): bool
    {
        if (Storage::disk($this->disk)->exists($this->filepath)) {
            Storage::disk($this->disk)->delete($this->filepath);
        }
        return true;
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($file) {
            $file->deleteFile();
        });
    }

    /**
 * Get the file icon attribute.
 */
public function getFileIconAttribute(): string
{
    if ($this->is_image) {
        return 'fa-image';
    }
    
    if ($this->is_pdf) {
        return 'fa-file-pdf';
    }
    
    if ($this->is_document) {
        if (str_contains($this->mime_type, 'word')) {
            return 'fa-file-word';
        }
        if (str_contains($this->mime_type, 'excel')) {
            return 'fa-file-excel';
        }
        if (str_contains($this->mime_type, 'powerpoint')) {
            return 'fa-file-powerpoint';
        }
        return 'fa-file-alt';
    }

    if (str_contains($this->mime_type, 'zip') || str_contains($this->mime_type, 'compressed')) {
        return 'fa-file-archive';
    }

    return 'fa-file';
}
}