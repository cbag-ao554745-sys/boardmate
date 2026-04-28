<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'room_number' => ['required', 'string', 'unique:room,room_number'],
            'floor' => ['required', 'integer', 'min:1'],
            'monthly_rent' => ['required', 'numeric', 'min:0'],
        ];
    }
}
