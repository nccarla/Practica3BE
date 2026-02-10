<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'email', 'unique:users,email'],
            'hiring_date' => ['sometimes', 'date'],
            'dui' => ['required', 'string', 'size:10', 'regex:/^\d{8}-\d{1}$/', 'unique:users,dui'],
            'phone_number' => ['sometimes', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'birth_date' => ['required', 'date', 'before:today'],
        ];
    }
}
