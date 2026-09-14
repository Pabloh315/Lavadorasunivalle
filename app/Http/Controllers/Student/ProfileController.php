<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __invoke()
    {
        return view('student.profile', ['user' => Auth::user()]);
    }
}
