<?php

namespace App\Http\Requests\Api\V1\System;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CellRequest extends FormRequest
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
            'name' => ['required', 'string' ,'max:255'],
            'system_id' => ['required', Rule::exists('systems','id')],
            'cell_id' => ['required',Rule::unique('cells','cell_id')],
            'max_capacity' => ['required', 'numeric' ],
            'lat' => ['required', 'numeric' ],
            'long' => ['required', 'numeric' ],
        ];
    }
}
