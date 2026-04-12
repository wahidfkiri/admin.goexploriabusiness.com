<?php

namespace Vendor\Chatbot\Requests\InternalChat;

use Illuminate\Foundation\Http\FormRequest;

class SendFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $maxKb   = config('internal_chat.max_file_size', 10240);  // 10 Mo par défaut
        $allowed = implode(',', config('internal_chat.allowed_file_types', [
            'jpg', 'jpeg', 'png', 'gif', 'webp',
            'pdf', 'doc', 'docx', 'xls', 'xlsx',
            'txt', 'zip', 'mp4', 'mp3',
        ]));

        return [
            'file' => "required|file|mimes:{$allowed}|max:{$maxKb}",
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'Veuillez sélectionner un fichier.',
            'file.mimes'    => 'Ce type de fichier n\'est pas autorisé.',
            'file.max'      => 'Le fichier dépasse la taille maximale autorisée (' . config('internal_chat.max_file_size', 10240) / 1024 . ' Mo).',
        ];
    }
}


