<?php

namespace Vendor\Cms\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ThemeUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'theme_file' => 'required|file|mimes:zip|max:10240', // Max 10MB
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom du thème est requis',
            'theme_file.required' => 'Le fichier ZIP est requis',
            'theme_file.mimes' => 'Le fichier doit être au format ZIP',
            'theme_file.max' => 'Le fichier ne doit pas dépasser 10MB',
        ];
    }
}