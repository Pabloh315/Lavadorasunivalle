<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterStudentRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin(string $guard = 'student')
    {
        return view('auth.login', ['guard' => $guard]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Credenciales invalidas.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(Auth::user()->isLaundryStaff() ? route('staff.dashboard') : route('student.dashboard'));
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(RegisterStudentRequest $request)
    {
        $data = $request->validated();
        $user = User::create($data);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('student.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
