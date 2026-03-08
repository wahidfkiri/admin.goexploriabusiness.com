<?php

namespace Vendor\Customer\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Change to true to allow access
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'user_id' => 'nullable|exists:users,id',
            'create_user' => 'nullable|boolean',
        ];

        // Add user creation rules conditionally
        if ($this->boolean('create_user')) {
            $rules['user_name'] = 'required|string|max:255';
            $rules['user_email'] = 'required|email|unique:users,email';
            $rules['user_password'] = 'required|string|min:8';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Le nom est requis.',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'phone.max' => 'Le numéro de téléphone ne peut pas dépasser 20 caractères.',
            'country.max' => 'Le pays ne peut pas dépasser 100 caractères.',
            'city.max' => 'La ville ne peut pas dépasser 100 caractères.',
            'state.max' => 'La région ne peut pas dépasser 100 caractères.',
            'postal_code.max' => 'Le code postal ne peut pas dépasser 20 caractères.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'email.max' => 'L\'email ne peut pas dépasser 255 caractères.',
            'user_name.required' => 'Le nom d\'utilisateur est requis.',
            'user_email.required' => 'L\'email d\'utilisateur est requis.',
            'user_email.email' => 'L\'email d\'utilisateur doit être valide.',
            'user_email.unique' => 'Cet email est déjà utilisé.',
            'user_password.required' => 'Le mot de passe est requis.',
            'user_password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nom',
            'phone' => 'téléphone',
            'country' => 'pays',
            'address' => 'adresse',
            'city' => 'ville',
            'state' => 'région',
            'postal_code' => 'code postal',
            'email' => 'email',
            'user_id' => 'utilisateur',
            'user_name' => 'nom d\'utilisateur',
            'user_email' => 'email d\'utilisateur',
            'user_password' => 'mot de passe',
        ];
    }
}