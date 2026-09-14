<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'student_code' => 'STD-'.fake()->unique()->numerify('#####'),
            'campus' => fake()->randomElement(['Central', 'Norte', 'Sur']),
            'role' => 'student',
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('Password123!'),
            'remember_token' => Str::random(10),
        ];
    }

    public function staff(): static
    {
        return $this->state(fn () => [
            'student_code' => null,
            'campus' => 'Lavanderia Central',
            'role' => 'laundry_staff',
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn () => ['email_verified_at' => null]);
    }
}
