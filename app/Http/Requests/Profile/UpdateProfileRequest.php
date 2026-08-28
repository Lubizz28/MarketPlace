<?php

namespace App\Http\Requests\Profile;

use App\Enums\Gender;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $userId = auth()->id();

        return [
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($userId)],
            'bio' => ['nullable', 'string', 'max:500'],
            'gender' => ['nullable', Rule::enum(Gender::class)],
            'birthdate' => ['nullable', 'date', 'before:today'],
        ];
    }
}
