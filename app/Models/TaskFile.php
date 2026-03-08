<?php
// app/Models/TaskFile.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class TaskFile extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'task_files';

    protected $fillable = [
        'task_id',
        'user_id',                 // Changé de 'uploaded_by' à 'user_id'
        'file_name',               // Changé de 'name' à 'file_name'
        'original_name',
        'file_path',                // Changé de 'path' à 'file_path'
        'file_size',                // Changé de 'size' à 'file_size'
        'mime_type',
        'file_extension',           // Changé de 'extension' à 'file_extension'
        'storage_disk',             // Changé de 'disk' à 'storage_disk'
        'description',
        'custom_properties',        // Changé de 'metadata' à 'custom_properties'
        'is_public',
        'is_temporary',
        'expires_at',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'custom_properties' => 'json',
        'is_public' => 'boolean',
        'is_temporary' => 'boolean',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $appends = [
        'url',
        'formatted_size',
        'icon',
        'is_image',
        'is_expired',
        'download_url',
    ];

    /**
     * Relations
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()  // Changé de 'uploader' à 'user'
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Accesseurs
     */
    public function getUrlAttribute(): string
    {
        return Storage::disk($this->storage_disk)->url($this->file_path);
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size ?? 0;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getIconAttribute(): string
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
        $documentExtensions = ['pdf', 'doc', 'docx', 'txt', 'rtf', 'odt'];
        $spreadsheetExtensions = ['xls', 'xlsx', 'csv', 'ods'];
        $presentationExtensions = ['ppt', 'pptx', 'odp'];
        $archiveExtensions = ['zip', 'rar', '7z', 'tar', 'gz', 'bz2'];
        $audioExtensions = ['mp3', 'wav', 'ogg', 'flac', 'aac'];
        $videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv'];
        
        $ext = strtolower($this->file_extension);
        
        if (in_array($ext, $imageExtensions)) {
            return 'fas fa-file-image text-info';
        } elseif (in_array($ext, $documentExtensions)) {
            return $ext === 'pdf' ? 'fas fa-file-pdf text-danger' : 'fas fa-file-word text-primary';
        } elseif (in_array($ext, $spreadsheetExtensions)) {
            return 'fas fa-file-excel text-success';
        } elseif (in_array($ext, $presentationExtensions)) {
            return 'fas fa-file-powerpoint text-warning';
        } elseif (in_array($ext, $archiveExtensions)) {
            return 'fas fa-file-archive text-secondary';
        } elseif (in_array($ext, $audioExtensions)) {
            return 'fas fa-file-audio';
        } elseif (in_array($ext, $videoExtensions)) {
            return 'fas fa-file-video';
        } else {
            return 'fas fa-file text-muted';
        }
    }

    public function getIsImageAttribute(): bool
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
        return in_array(strtolower($this->file_extension), $imageExtensions);
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getDownloadUrlAttribute(): string
    {
        return route('tasks.files.download', ['task' => $this->task_id, 'file' => $this->id]);
    }

    public function getPreviewUrlAttribute(): ?string
    {
        if ($this->is_image) {
            return route('tasks.files.preview', ['task' => $this->task_id, 'file' => $this->id]);
        }
        
        $extension = strtolower($this->file_extension);
        if ($extension === 'pdf') {
            return route('tasks.files.preview', ['task' => $this->task_id, 'file' => $this->id]);
        }
        
        return null;
    }

    /**
     * Scopes
     */
    public function scopeImages($query)
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
        return $query->whereIn('file_extension', $imageExtensions);
    }

    public function scopeDocuments($query)
    {
        $documentExtensions = ['pdf', 'doc', 'docx', 'txt', 'rtf', 'odt'];
        return $query->whereIn('file_extension', $documentExtensions);
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeTemporary($query)
    {
        return $query->where('is_temporary', true);
    }

    public function scopeByTask($query, $taskId)
    {
        return $query->where('task_id', $taskId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByExtension($query, $extension)
    {
        return $query->where('file_extension', $extension);
    }

    /**
     * Méthodes
     */
    public function deleteFile()
    {
        // Supprimer le fichier physique
        Storage::disk($this->storage_disk)->delete($this->file_path);
        
        // Supprimer le dossier parent s'il est vide
        $directory = dirname(storage_path('app/' . $this->file_path));
        if (is_dir($directory) && count(scandir($directory)) == 2) {
            rmdir($directory);
        }
        
        // Supprimer l'enregistrement
        return $this->delete();
    }

    public function copyTo(Task $task): self
    {
        $newPath = 'tasks/' . $task->id . '/' . $this->file_name;
        
        Storage::disk($this->storage_disk)->copy($this->file_path, $newPath);
        
        $customProps = json_decode($this->custom_properties, true) ?? [];
        $customProps['copied_from'] = $this->id;
        $customProps['copied_at'] = now()->toDateTimeString();
        $customProps['original_task'] = $this->task_id;
        
        $newFile = $this->replicate();
        $newFile->task_id = $task->id;
        $newFile->file_path = $newPath;
        $newFile->user_id = auth()->id();
        $newFile->created_at = now();
        $newFile->custom_properties = json_encode($customProps);
        $newFile->save();
        
        return $newFile;
    }

    public function markAsTemporary($expiresAt = null)
    {
        $this->is_temporary = true;
        $this->expires_at = $expiresAt ?? now()->addDays(7);
        $this->save();
        
        return $this;
    }

    public function markAsPermanent()
    {
        $this->is_temporary = false;
        $this->expires_at = null;
        $this->save();
        
        return $this;
    }

    public function togglePublic()
    {
        $this->is_public = !$this->is_public;
        $this->save();
        
        return $this;
    }
}