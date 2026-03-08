<?php

namespace Vendor\Website\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWebsiteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'categorie_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'url' => [
                'required',
                'string',
                'max:255',
                Rule::unique('websites')->ignore($this->website)
            ],
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,maintenance,development',
            'template_type' => 'nullable|in:basic,premium,enterprise,custom',
            'color_scheme' => 'nullable|string|max:50',
            'features' => 'nullable|array',
            'price' => 'nullable|numeric|min:0',
            'screenshot_path' => 'nullable|string|max:500'
        ];
    }

    public function messages(): array
    {
        return [
            'url.unique' => 'Cette URL est déjà utilisée par un autre site web.',
        ];
    }
}