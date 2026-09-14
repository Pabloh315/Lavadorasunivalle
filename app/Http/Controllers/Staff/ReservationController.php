<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\UpdateReservationStatusRequest;
use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $reservations = Reservation::with(['user', 'washer'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('date'), fn ($query) => $query->whereDate('reservation_date', $request->date('date')))
            ->when($request->filled('student'), function ($query) use ($request) {
                $term = '%'.$request->string('student')->toString().'%';
                $query->whereHas('user', fn ($userQuery) => $userQuery
                    ->where('name', 'like', $term)
                    ->orWhere('last_name', 'like', $term)
                    ->orWhere('student_code', 'like', $term)
                    ->orWhere('email', 'like', $term));
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('staff.reservations.index', ['reservations' => $reservations]);
    }

    public function update(UpdateReservationStatusRequest $request, Reservation $reservation, ReservationService $service)
    {
        $service->advanceStatus($reservation, $request->validated('status'));

        return back()->with('status', 'Estado actualizado.');
    }
}
