<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'label' => ['required', 'string', 'max:50'],
            'recipient_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:25'],
            'address_line' => ['required', 'string', 'max:500'],
            'province_name' => ['required', 'string', 'max:100'],
            'city_name' => ['required', 'string', 'max:100'],
            'district_name' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:10'],
            'is_primary' => ['nullable', 'boolean'],
        ];
    }
}
