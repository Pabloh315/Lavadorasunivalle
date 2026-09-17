<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Washer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->staff()->create([
            'name' => 'Laura',
            'last_name' => 'Operaciones',
            'email' => 'personal@quikwash.test',
            'password' => Hash::make('Password123!'),
        ]);

        $students = collect([
            ['Ana', 'Rojas', 'QW-2026-001', 'Central'],
            ['Bruno', 'Mendez', 'QW-2026-002', 'Central'],
            ['Camila', 'Vargas', 'QW-2026-003', 'Norte'],
            ['Diego', 'Salinas', 'QW-2026-004', 'Sur'],
            ['Elena', 'Torrez', 'QW-2026-005', 'Central'],
            ['Fabian', 'Quiroga', 'QW-2026-006', 'Norte'],
            ['Gabriela', 'Flores', 'QW-2026-007', 'Sur'],
            ['Hugo', 'Paz', 'QW-2026-008', 'Central'],
            ['Irene', 'Lopez', 'QW-2026-009', 'Norte'],
            ['Javier', 'Campos', 'QW-2026-010', 'Sur'],
        ])->map(fn ($student, $index) => User::factory()->create([
            'name' => $student[0],
            'last_name' => $student[1],
            'student_code' => $student[2],
            'campus' => $student[3],
            'email' => 'estudiante'.($index + 1).'@quikwash.test',
            'password' => Hash::make('Password123!'),
        ]));

        $washers = collect(range(1, 10))->map(fn ($number) => Washer::create([
            'code' => sprintf('LW-%03d', $number),
            'name' => sprintf('Lavadora %02d', $number),
            'status' => 'available',
        ]));

        $examples = [
            ['student' => 0, 'washer' => 0, 'days' => 1, 'time' => '08:00', 'garments' => 8, 'status' => 'pending', 'notes' => 'Prendas deportivas'],
            ['student' => 1, 'washer' => 1, 'days' => 1, 'time' => '09:00', 'garments' => 12, 'status' => 'in_progress', 'notes' => 'Separar ropa clara'],
            ['student' => 2, 'washer' => 2, 'days' => 2, 'time' => '10:00', 'garments' => 6, 'status' => 'pending', 'notes' => null],
            ['student' => 3, 'washer' => 3, 'days' => -1, 'time' => '11:00', 'garments' => 15, 'status' => 'completed', 'notes' => 'Entregado'],
            ['student' => 4, 'washer' => 4, 'days' => 3, 'time' => '12:00', 'garments' => 9, 'status' => 'cancelled', 'notes' => 'Cancelada por estudiante'],
        ];

        foreach ($examples as $example) {
            Reservation::create([
                'user_id' => $students[$example['student']]->id,
                'washing_machine_id' => $washers[$example['washer']]->id,
                'reservation_date' => now()->addDays($example['days'])->toDateString(),
                'reservation_time' => $example['time'],
                'garments_count' => $example['garments'],
                'status' => $example['status'],
                'notes' => $example['notes'],
                'cancelled_at' => $example['status'] === 'cancelled' ? now() : null,
            ]);
        }
    }
}
