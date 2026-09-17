<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Washer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reservation>
 */
class ReservationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'washing_machine_id' => Washer::factory(),
            'reservation_date' => now()->addDays(fake()->numberBetween(1, 8))->toDateString(),
            'reservation_time' => fake()->randomElement(Reservation::HOURS),
            'garments_count' => fake()->numberBetween(3, 30),
            'notes' => null,
            'status' => 'pending',
            'cancelled_at' => null,
        ];
    }
}
