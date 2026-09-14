<?php

namespace App\Http\Requests\Student;

use App\Models\Reservation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isStudent() ?? false;
    }

    public function rules(): array
    {
        return [
            'washing_machine_id' => ['required', 'exists:washing_machines,id'],
            'reservation_date' => ['required', 'date'],
            'reservation_time' => ['required', Rule::in(Reservation::HOURS)],
            'weight_kg' => ['required', 'numeric', 'min:0.1', 'max:30'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }
}
