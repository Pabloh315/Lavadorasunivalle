<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Washer;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();
        $reservations = $user->reservations()->with('washer')->latest()->get();
        $nextReservation = $user->reservations()
            ->with('washer')
            ->whereIn('status', Reservation::ACTIVE_STATUSES)
            ->orderBy('reservation_date')
            ->orderBy('reservation_time')
            ->first();

        return view('student.dashboard', [
            'activeCount' => $reservations->whereIn('status', Reservation::ACTIVE_STATUSES)->count(),
            'pendingCount' => $reservations->where('status', 'pending')->count(),
            'completedCount' => $reservations->where('status', 'completed')->count(),
            'nextReservation' => $nextReservation,
            'washers' => Washer::withCount(['reservations as active_reservations_count' => fn ($query) => $query->whereIn('status', Reservation::ACTIVE_STATUSES)])->orderBy('code')->get(),
        ]);
    }
}
