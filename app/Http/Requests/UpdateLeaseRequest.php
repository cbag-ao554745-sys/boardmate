<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'status' => ['nullable', 'in:Active,Completed,Terminated'],
        ];
    }
}
