<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Washer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class QuikWashFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_registration_assigns_student_role_and_ignores_role_input(): void
    {
        $this->withSession(['_token' => 'test-token'])->post('/register', [
            'name' => 'Nuevo',
            'last_name' => 'Estudiante',
            'student_code' => 'UNI-001',
            'campus' => 'Central',
            'email' => 'nuevo@quikwash.test',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'laundry_staff',
            '_token' => 'test-token',
        ])->assertRedirect('/student/dashboard');

        $this->assertDatabaseHas('users', [
            'email' => 'nuevo@quikwash.test',
            'role' => 'student',
            'student_code' => 'UNI-001',
        ]);
    }

    public function test_login_redirects_by_role(): void
    {
        User::factory()->create(['email' => 'student@test.local', 'password' => Hash::make('Password123!')]);
        User::factory()->staff()->create(['email' => 'staff@test.local', 'password' => Hash::make('Password123!')]);

        $this->withSession(['_token' => 'test-token'])->post('/login', [
            'email' => 'student@test.local',
            'password' => 'Password123!',
            '_token' => 'test-token',
        ])->assertRedirect('/student/dashboard');

        $this->withSession(['_token' => 'test-token'])->post('/logout', ['_token' => 'test-token']);

        $this->withSession(['_token' => 'test-token'])->post('/login', [
            'email' => 'staff@test.local',
            'password' => 'Password123!',
            '_token' => 'test-token',
        ])->assertRedirect('/staff/dashboard');
    }

    public function test_student_cannot_access_staff_routes(): void
    {
        $student = User::factory()->create();

        $this->actingAs($student)->get('/staff/dashboard')->assertForbidden();
    }

    public function test_staff_can_see_all_reservations(): void
    {
        $staff = User::factory()->staff()->create();
        Reservation::factory()->count(2)->create();

        $this->actingAs($staff)->get('/staff/reservations')->assertOk();
    }

    public function test_student_sees_only_own_reservations(): void
    {
        $student = User::factory()->create();
        $other = User::factory()->create();
        $own = Reservation::factory()->create(['user_id' => $student->id]);
        $foreign = Reservation::factory()->create(['user_id' => $other->id]);

        $this->actingAs($student)->get('/student/reservations')
            ->assertSee($own->washer->code)
            ->assertDontSee($foreign->washer->code);
    }

    public function test_exactly_ten_initial_washers_are_seeded(): void
    {
        $this->seed();

        $this->assertSame(10, Washer::count());
        $this->assertSame(10, Washer::where('status', 'available')->count());
        $this->assertDatabaseHas('washing_machines', ['code' => 'LW-001', 'name' => 'Lavadora 01']);
        $this->assertDatabaseHas('washing_machines', ['code' => 'LW-010', 'name' => 'Lavadora 10']);
    }

    public function test_student_code_and_email_are_unique(): void
    {
        User::factory()->create(['student_code' => 'DUP-001', 'email' => 'dup@quikwash.test']);

        $this->withSession(['_token' => 'test-token'])->post('/register', [
            'name' => 'Otra',
            'last_name' => 'Persona',
            'student_code' => 'DUP-001',
            'campus' => 'Central',
            'email' => 'otra@quikwash.test',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            '_token' => 'test-token',
        ])->assertSessionHasErrors('student_code');

        $this->withSession(['_token' => 'test-token'])->post('/register', [
            'name' => 'Otra',
            'last_name' => 'Persona',
            'student_code' => 'DUP-002',
            'campus' => 'Central',
            'email' => 'dup@quikwash.test',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            '_token' => 'test-token',
        ])->assertSessionHasErrors('email');
    }
}
