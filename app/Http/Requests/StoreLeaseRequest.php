<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'room_id' => ['required', 'exists:room,room_id'],
            'tenant_id' => ['required', 'exists:tenant,tenant_id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'monthly_rent' => ['required', 'numeric', 'min:0'],
            'deposit_amount' => ['nullable', 'numeric', 'min:0'],
            'initial_payment' => ['nullable', 'numeric', 'min:0'],
            'payment_due_day' => ['required', 'integer', 'between:1,31'],
        ];
    }
}
