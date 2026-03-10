<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'service_date' => ['required', 'date'],
            'service_type' => ['required', 'in:interim,full,major,repair,diagnostic'],
            'description' => ['required', 'string'],
            'technician' => ['required', 'string', 'max:255'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'mileage' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
            'next_service_due' => ['nullable', 'date', 'after:service_date'],
        ];
    }
}