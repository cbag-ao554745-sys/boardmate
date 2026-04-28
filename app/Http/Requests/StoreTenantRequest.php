<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:50'],
            'middle_name' => ['nullable', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'contact_number' => ['required', 'string', 'regex:/^[0-9]{10,}$/'],
            'date_of_birth' => ['required', 'date'],
            'gender' => ['required', 'in:Male,Female,Other'],
            'address_line_1' => ['required', 'string'],
            'address_line_2' => ['nullable', 'string'],
            'city' => ['required', 'string'],
            'province' => ['required', 'string'],
            'postal_code' => ['required', 'string'],
            'guardian_first_name' => ['nullable', 'string', 'max:50'],
            'guardian_middle_name' => ['nullable', 'string', 'max:50'],
            'guardian_last_name' => ['nullable', 'string', 'max:50'],
            'guardian_contact_number' => ['nullable', 'string', 'regex:/^[0-9]{10,}$/'],
            'guardian_address_line_1' => ['nullable', 'string'],
            'guardian_address_line_2' => ['nullable', 'string'],
            'guardian_city' => ['nullable', 'string'],
            'guardian_province' => ['nullable', 'string'],
            'guardian_postal_code' => ['nullable', 'string'],
        ];
    }
}
