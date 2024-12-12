<?php

namespace App\Http\Requests\Api\V1\Auth\Email;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeEmailVerifyRequest extends FormRequest
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
            "email" => ['required', 'string', 'email:rfc,dns', Rule::unique('users', 'email')->ignore(auth('api')->id(), 'id')],
            'otp_token' => ['required', 'string'],
            'otp' => ['required', 'string'],
        ];
    }
}
