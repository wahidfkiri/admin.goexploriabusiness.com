<?php

namespace Vendor\Chatbot\Services;

use Vendor\Chatbot\Models\ChatFile;
use Vendor\Chatbot\Models\ChatMessage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    protected string $disk;
    protected string $basePath;

    public function __construct()
    {
        $this->disk     = config('chatbot.storage_disk', 'public');
        $this->basePath = config('chatbot.storage_path', 'chatbot/files');
    }

    /**
     * Stocke un fichier uploadé et crée l'enregistrement ChatFile.
     */
    public function store(UploadedFile $file, ChatMessage $message): ChatFile
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename  = Str::uuid() . '.' . $extension;
        $isImage   = str_starts_with($file->getMimeType(), 'image/');

        // Sous-dossier par date pour éviter trop de fichiers dans un seul dossier
        $subPath = $this->basePath . '/' . now()->format('Y/m');
        $path    = $file->storeAs($subPath, $filename, $this->disk);

        $width  = null;
        $height = null;

        // Dimensions pour les images
        if ($isImage) {
            try {
                [$width, $height] = getimagesize($file->getRealPath());
            } catch (\Exception) {}
        }

        return ChatFile::create([
            'message_id'    => $message->id,
            'room_id'       => $message->room_id,
            'filename'      => $filename,
            'original_name' => $file->getClientOriginalName(),
            'path'          => $path,
            'mime_type'     => $file->getMimeType(),
            'extension'     => $extension,
            'size'          => $file->getSize(),
            'width'         => $width,
            'height'        => $height,
            'is_image'      => $isImage,
        ]);
    }

    /**
     * Supprime un fichier du disque et de la base.
     */
    public function delete(ChatFile $file): bool
    {
        if (Storage::disk($this->disk)->exists($file->path)) {
            Storage::disk($this->disk)->delete($file->path);
        }

        return $file->delete();
    }

    public function validateMime(UploadedFile $file): bool
    {
        $allowed = config('chatbot.allowed_file_types', []);
        return in_array(strtolower($file->getClientOriginalExtension()), $allowed);
    }
}