<?php

namespace Vendor\Chatbot\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileUploadRequest extends FormRequest
{
    public function rules(): array
    {
        $maxKb    = config('chatbot.max_file_size', 10240);
        $allowed  = implode(',', config('chatbot.allowed_file_types', ['jpg', 'jpeg', 'png', 'pdf']));

        return [
            'file'    => "required|file|mimes:{$allowed}|max:{$maxKb}",
            'room_id' => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'Veuillez sélectionner un fichier.',
            'file.max'      => 'Le fichier dépasse la taille maximale autorisée.',
            'file.mimes'    => 'Ce type de fichier n\'est pas autorisé.',
        ];
    }
}