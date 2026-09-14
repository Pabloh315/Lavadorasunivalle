<?php

namespace App\Http\Requests\Staff;

use App\Models\Washer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWasherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isLaundryStaff() ?? false;
    }

    public function rules(): array
    {
        return ['status' => ['required', Rule::in(Washer::STATUSES)]];
    }
}
