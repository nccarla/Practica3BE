<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('user')?->id ?? $this->route('id');

        return [
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($userId),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'hiring_date' => ['sometimes', 'date'],
            'dui' => [
                'required',
                'string',
                'size:10',
                'regex:/^\d{8}-\d{1}$/',
                Rule::unique('users', 'dui')->ignore($userId),
            ],
            'phone_number' => ['sometimes', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'birth_date' => ['required', 'date', 'before:today'],
        ];
    }
}

