<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
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
            'room_number' => ['required', 'string', 'max:50'],
            'monthly_rent' => ['required', 'numeric', 'min:0'],
            'water_fee' => ['required', 'numeric', 'min:0'],
            'electric_fee' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:vacant,occupied,full'],
            'image' => ['nullable', 'image', 'max:2048'],
            'additional_images.*' => ['nullable', 'image', 'max:2048'],
            'additional_images' => ['nullable', 'array', 'max:5'],
        ];
    }
}
