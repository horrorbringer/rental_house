<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomRequest extends FormRequest
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
            'building_id' => ['required', 'exists:buildings,id'],
            'room_number' => ['required', 'string', 'max:10'],
            'monthly_rent' => ['required', 'numeric', 'min:0'],
            'water_fee' => ['required', 'numeric', 'min:0'],
            'electric_fee' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:vacant,occupied'],
            'image' => ['nullable', 'image', 'max:10240'], // 10MB max
            'additional_images.*' => ['nullable', 'image', 'max:10240'], // 10MB max
        ];
    }
}
