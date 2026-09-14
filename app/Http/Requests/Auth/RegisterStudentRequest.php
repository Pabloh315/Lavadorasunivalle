<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'student_code' => ['required', 'string', 'max:30', 'unique:users,student_code'],
            'campus' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email', 'max:120', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ];
    }
}
