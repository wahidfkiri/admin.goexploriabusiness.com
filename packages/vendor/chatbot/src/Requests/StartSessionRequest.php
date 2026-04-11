<?php

namespace Vendor\Chatbot\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StartSessionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'visitor_name'  => 'nullable|string|max:100',
            'visitor_email' => 'nullable|email|max:255',
            'subject'       => 'nullable|string|max:200',
            'page_url'      => 'nullable|url',
            'extra'         => 'nullable|array',
        ];
    }
}