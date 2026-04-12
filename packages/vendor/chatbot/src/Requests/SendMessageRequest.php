<?php

namespace Vendor\Chatbot\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'body' => 'required_without:file|nullable|string|max:2000|min:1',
            'type' => 'sometimes|in:text,file',
        ];
    }

    public function messages(): array
    {
        return [
            'body.required_without' => 'Le message ne peut pas être vide.',
            'body.max'              => 'Le message ne peut pas dépasser 2000 caractères.',
            'body.min'              => 'Le message ne peut pas être vide.',
        ];
    }

    /**
     * Nettoie le body avant validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('body')) {
            $this->merge(['body' => trim($this->input('body'))]);
        }
    }
}


