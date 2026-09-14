<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\Staff\ReservationController as StaffReservationController;
use App\Http\Controllers\Staff\StudentController as StaffStudentController;
use App\Http\Controllers\Staff\WasherController as StaffWasherController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Student\ReservationController as StudentReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/staff/login', fn (AuthController $controller) => $controller->showLogin('staff'))->name('staff.login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', StudentDashboardController::class)->name('dashboard');
    Route::get('/reservations', [StudentReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/create', [StudentReservationController::class, 'create'])->name('reservations.create');
    Route::get('/reservations/available-hours', [StudentReservationController::class, 'availableHours'])->name('reservations.available-hours');
    Route::post('/reservations', [StudentReservationController::class, 'store'])->name('reservations.store');
    Route::patch('/reservations/{reservation}/cancel', [StudentReservationController::class, 'cancel'])->name('reservations.cancel');
    Route::get('/profile', ProfileController::class)->name('profile');
});

Route::middleware(['auth', 'role:laundry_staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', StaffDashboardController::class)->name('dashboard');
    Route::get('/reservations', [StaffReservationController::class, 'index'])->name('reservations.index');
    Route::patch('/reservations/{reservation}', [StaffReservationController::class, 'update'])->name('reservations.update');
    Route::get('/washers', [StaffWasherController::class, 'index'])->name('washers.index');
    Route::patch('/washers/{washer}', [StaffWasherController::class, 'update'])->name('washers.update');
    Route::get('/students', [StaffStudentController::class, 'index'])->name('students.index');
});
