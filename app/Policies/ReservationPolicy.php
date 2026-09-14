<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;

class ReservationPolicy
{
    public function view(User $user, Reservation $reservation): bool
    {
        return $user->isLaundryStaff() || $reservation->user_id === $user->id;
    }

    public function cancel(User $user, Reservation $reservation): bool
    {
        if ($reservation->status === 'completed' || $reservation->status === 'cancelled') {
            return false;
        }

        if ($user->isLaundryStaff()) {
            return true;
        }

        return $reservation->user_id === $user->id && $reservation->status === 'pending';
    }
}
