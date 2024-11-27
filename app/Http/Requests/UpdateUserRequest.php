<?php

namespace App\Http\Requests;

use App\Enums\StatusTypes;
use Illuminate\Validation\Rules\Enum;
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
        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user)],
            'phone' => 'required|string|max:20',
            'registered_at' => 'required|date',
            'status' => ['required', new Enum(StatusTypes::class)],
            'city' => 'nullable|string|max:255',
        ];
    }
}
