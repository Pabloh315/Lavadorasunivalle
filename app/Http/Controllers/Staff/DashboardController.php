<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Washer;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $today = today()->toDateString();

        return view('staff.dashboard', [
            'todayCount' => Reservation::whereDate('reservation_date', $today)->count(),
            'pendingCount' => Reservation::where('status', 'pending')->count(),
            'inProgressCount' => Reservation::where('status', 'in_progress')->count(),
            'completedCount' => Reservation::where('status', 'completed')->count(),
            'cancelledCount' => Reservation::where('status', 'cancelled')->count(),
            'availableWashers' => Washer::where('status', 'available')->count(),
            'maintenanceWashers' => Washer::where('status', 'maintenance')->count(),
            'reservations' => Reservation::with(['user', 'washer'])->orderBy('reservation_date')->orderBy('reservation_time')->limit(8)->get(),
        ]);
    }
}
