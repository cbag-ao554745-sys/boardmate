<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $roomId = $this->route('room')?->room_id;

        return [
            'room_number' => ['nullable', 'string', 'unique:room,room_number,' . $roomId . ',room_id'],
            'floor' => ['nullable', 'integer', 'min:1'],
            'monthly_rent' => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', 'in:Available,Occupied,Under Maintenance'],
        ];
    }
}
