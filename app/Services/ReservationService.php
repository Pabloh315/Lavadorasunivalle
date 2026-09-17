<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Washer;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class ReservationService
{
    public function create(User $student, array $data): Reservation
    {
        $washer = Washer::findOrFail($data['washing_machine_id']);
        $this->ensureCanReserve($student, $washer, $data['reservation_date'], $data['reservation_time']);

        return Reservation::create([
            'user_id' => $student->id,
            'washing_machine_id' => $washer->id,
            'reservation_date' => $data['reservation_date'],
            'reservation_time' => $data['reservation_time'],
            'garments_count' => $data['garments_count'],
            'notes' => $data['notes'] ?? null,
            'status' => 'pending',
        ]);
    }

    public function ensureCanReserve(User $student, Washer $washer, string $date, string $time): void
    {
        if (! $student->isStudent()) {
            throw ValidationException::withMessages(['washing_machine_id' => 'Solo los estudiantes pueden crear reservas.']);
        }

        if (! $washer->isReservable()) {
            throw ValidationException::withMessages(['washing_machine_id' => 'La lavadora seleccionada no esta disponible.']);
        }

        if (Carbon::parse($date.' '.$time)->isPast()) {
            throw ValidationException::withMessages(['reservation_date' => 'No se permiten reservas en fechas u horas pasadas.']);
        }

        if ($student->reservations()->whereIn('status', Reservation::ACTIVE_STATUSES)->count() >= 3) {
            throw ValidationException::withMessages(['washing_machine_id' => 'Solo puedes tener hasta 3 reservas activas.']);
        }

        if ($this->slotTaken($washer->id, $date, $time)) {
            throw ValidationException::withMessages(['reservation_time' => 'Esta lavadora ya esta reservada en ese horario.']);
        }
    }

    public function availableHours(Washer $washer, string $date): array
    {
        if (! $washer->isReservable()) {
            return [];
        }

        return array_values(array_filter(Reservation::HOURS, function (string $hour) use ($washer, $date): bool {
            return ! Carbon::parse($date.' '.$hour)->isPast() && ! $this->slotTaken($washer->id, $date, $hour);
        }));
    }

    public function cancel(Reservation $reservation): Reservation
    {
        if ($reservation->status !== 'pending' || $reservation->isPastSlot()) {
            throw ValidationException::withMessages(['status' => 'Esta reserva ya no se puede cancelar.']);
        }

        $reservation->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return $reservation;
    }

    public function advanceStatus(Reservation $reservation, string $status): Reservation
    {
        $allowed = [
            'pending' => ['in_progress', 'cancelled'],
            'in_progress' => ['completed', 'cancelled'],
            'completed' => [],
            'cancelled' => [],
        ];

        if (! in_array($status, $allowed[$reservation->status] ?? [], true)) {
            throw ValidationException::withMessages(['status' => 'Transicion de estado no permitida.']);
        }

        $reservation->update([
            'status' => $status,
            'cancelled_at' => $status === 'cancelled' ? now() : $reservation->cancelled_at,
        ]);

        return $reservation;
    }

    private function slotTaken(int $washerId, string $date, string $time): bool
    {
        return Reservation::where('washing_machine_id', $washerId)
            ->whereDate('reservation_date', $date)
            ->where('reservation_time', $time)
            ->where('status', '!=', 'cancelled')
            ->exists();
    }
}
