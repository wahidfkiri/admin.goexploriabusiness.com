<?php

namespace Vendor\Customer\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Mettez votre logique d'autorisation ici
    }

    public function rules()
    {
        return [
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('customers')->ignore($this->customer)->whereNull('deleted_at')
            ],
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'The user field is required.',
            'user_id.exists' => 'The selected user does not exist.',
            'user_id.unique' => 'This user already has a customer profile.',
            'name.required' => 'The name field is required.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'phone.max' => 'The phone number may not be greater than 20 characters.',
            'country.max' => 'The country may not be greater than 100 characters.',
            'city.max' => 'The city may not be greater than 100 characters.',
            'state.max' => 'The state may not be greater than 100 characters.',
            'postal_code.max' => 'The postal code may not be greater than 20 characters.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'The email may not be greater than 255 characters.',
        ];
    }
}