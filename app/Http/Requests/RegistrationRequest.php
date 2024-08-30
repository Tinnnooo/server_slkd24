<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
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
            'name' => ['required'],
            'username' => ['required', 'unique:users,username'],
            'email' => ['required', 'unique:users,email'],
            'password' => ['required'],
            'date_of_birth' => ['required'],
            'phone_number' => ['required'],
            'profile_picture' => ['required', 'image', 'mimes:jpg,png,jpeg'],
            'captcha' => 'nullable',
            'token' => 'nullable'
        ];
    }
}
