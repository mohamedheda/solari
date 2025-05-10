<?php

namespace App\Http\Requests\Api\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'image' => ['nullable','image'],
            'old_password' => ['required_with:password','current_password:api' ,'string', 'min:8'],
            'password' => ['nullable', 'string', 'min:8', 'different:old_password'],
        ];
    }
    public function messages()
    {
        return [
            'old_password.required_with' => "The Old Password field is required when password is present."
        ];
    }
}
