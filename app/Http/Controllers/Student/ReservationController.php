<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreReservationRequest;
use App\Models\Reservation;
use App\Models\Washer;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ReservationController extends Controller
{
    public function index()
    {
        return view('student.reservations.index', [
            'reservations' => Auth::user()->reservations()->with('washer')->latest()->paginate(10),
        ]);
    }

    public function create(ReservationService $service)
    {
        $washers = Washer::where('status', 'available')->orderBy('code')->get();
        $selectedWasher = $washers->first();
        $selectedDate = now()->toDateString();

        return view('student.reservations.create', [
            'washers' => $washers,
            'hours' => $selectedWasher ? $service->availableHours($selectedWasher, $selectedDate) : [],
            'selectedDate' => $selectedDate,
        ]);
    }

    public function store(StoreReservationRequest $request, ReservationService $service)
    {
        $service->create(Auth::user(), $request->validated());

        return redirect()->route('student.reservations.index')->with('status', 'Reserva creada correctamente.');
    }

    public function availableHours(Request $request, ReservationService $service)
    {
        $data = $request->validate([
            'washing_machine_id' => ['required', 'exists:washing_machines,id'],
            'reservation_date' => ['required', 'date'],
        ]);

        return response()->json([
            'hours' => $service->availableHours(Washer::findOrFail($data['washing_machine_id']), $data['reservation_date']),
        ]);
    }

    public function cancel(Reservation $reservation, ReservationService $service)
    {
        Gate::authorize('cancel', $reservation);
        $service->cancel($reservation);

        return back()->with('status', 'Reserva cancelada.');
    }
}
