<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReservationStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isLaundryStaff() ?? false;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(['in_progress', 'completed', 'cancelled'])],
        ];
    }
}
