<?php

namespace Database\Factories;

use App\Models\Washer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Washer>
 */
class WasherFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => 'LW-'.fake()->unique()->numerify('###'),
            'name' => 'Lavadora '.fake()->numerify('##'),
            'status' => 'available',
        ];
    }
}
