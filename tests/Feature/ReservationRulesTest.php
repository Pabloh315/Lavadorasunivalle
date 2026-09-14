<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Washer;
use App\Services\ReservationService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ReservationRulesTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_have_only_three_active_reservations(): void
    {
        $student = User::factory()->create();
        $washer = Washer::factory()->create(['status' => 'available']);
        Reservation::factory()->count(3)->create(['user_id' => $student->id, 'status' => 'pending']);

        $this->expectException(ValidationException::class);

        app(ReservationService::class)->create($student, [
            'washing_machine_id' => $washer->id,
            'reservation_date' => now()->addDay()->toDateString(),
            'reservation_time' => '08:00',
            'weight_kg' => 5,
        ]);
    }

    public function test_completed_and_cancelled_reservations_do_not_count_as_active(): void
    {
        $student = User::factory()->create();
        $washer = Washer::factory()->create(['status' => 'available']);
        Reservation::factory()->count(3)->create(['user_id' => $student->id, 'status' => 'completed']);
        Reservation::factory()->count(3)->create(['user_id' => $student->id, 'status' => 'cancelled']);

        $reservation = app(ReservationService::class)->create($student, [
            'washing_machine_id' => $washer->id,
            'reservation_date' => now()->addDay()->toDateString(),
            'reservation_time' => '08:00',
            'weight_kg' => 5,
        ]);

        $this->assertSame('pending', $reservation->status);
    }

    public function test_washer_cannot_be_reserved_twice_in_same_active_slot(): void
    {
        $student = User::factory()->create();
        $otherStudent = User::factory()->create();
        $washer = Washer::factory()->create(['status' => 'available']);
        $date = now()->addDay()->toDateString();

        Reservation::factory()->create([
            'user_id' => $otherStudent->id,
            'washing_machine_id' => $washer->id,
            'reservation_date' => $date,
            'reservation_time' => '09:00',
            'status' => 'pending',
        ]);

        $this->expectException(ValidationException::class);

        app(ReservationService::class)->create($student, [
            'washing_machine_id' => $washer->id,
            'reservation_date' => $date,
            'reservation_time' => '09:00',
            'weight_kg' => 4,
        ]);
    }

    public function test_cancelled_reservation_releases_slot(): void
    {
        $student = User::factory()->create();
        $washer = Washer::factory()->create(['status' => 'available']);
        $date = now()->addDay()->toDateString();

        Reservation::factory()->create([
            'washing_machine_id' => $washer->id,
            'reservation_date' => $date,
            'reservation_time' => '10:00',
            'status' => 'cancelled',
        ]);

        $reservation = app(ReservationService::class)->create($student, [
            'washing_machine_id' => $washer->id,
            'reservation_date' => $date,
            'reservation_time' => '10:00',
            'weight_kg' => 4,
        ]);

        $this->assertSame('pending', $reservation->status);
    }

    public function test_maintenance_washer_cannot_be_reserved(): void
    {
        $this->assertWasherStatusCannotBeReserved('maintenance');
    }

    public function test_out_of_service_washer_cannot_be_reserved(): void
    {
        $this->assertWasherStatusCannotBeReserved('out_of_service');
    }

    public function test_past_dates_are_rejected(): void
    {
        $student = User::factory()->create();
        $washer = Washer::factory()->create(['status' => 'available']);

        $this->expectException(ValidationException::class);

        app(ReservationService::class)->create($student, [
            'washing_machine_id' => $washer->id,
            'reservation_date' => now()->subDay()->toDateString(),
            'reservation_time' => '12:00',
            'weight_kg' => 5,
        ]);
    }

    public function test_past_hours_today_are_rejected(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-09-14 10:30:00'));
        $student = User::factory()->create();
        $washer = Washer::factory()->create(['status' => 'available']);

        try {
            $this->expectException(ValidationException::class);

            app(ReservationService::class)->create($student, [
                'washing_machine_id' => $washer->id,
                'reservation_date' => '2026-09-14',
                'reservation_time' => '10:00',
                'weight_kg' => 5,
            ]);
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_weight_must_be_greater_than_zero(): void
    {
        $student = User::factory()->create();
        $washer = Washer::factory()->create(['status' => 'available']);

        $this->actingAs($student)->withSession(['_token' => 'test-token'])->post('/student/reservations', [
            'washing_machine_id' => $washer->id,
            'reservation_date' => now()->addDay()->toDateString(),
            'reservation_time' => '12:00',
            'weight_kg' => 0,
            '_token' => 'test-token',
        ])->assertSessionHasErrors('weight_kg');
    }

    public function test_student_can_cancel_only_own_pending_reservation(): void
    {
        $student = User::factory()->create();
        $otherStudent = User::factory()->create();
        $own = Reservation::factory()->create(['user_id' => $student->id, 'status' => 'pending']);
        $other = Reservation::factory()->create(['user_id' => $otherStudent->id, 'status' => 'pending']);
        $completed = Reservation::factory()->create(['user_id' => $student->id, 'status' => 'completed']);

        $this->assertTrue($student->can('cancel', $own));
        $this->assertFalse($student->can('cancel', $other));
        $this->assertFalse($student->can('cancel', $completed));
        $this->actingAs($student)->withSession(['_token' => 'test-token'])->patch('/student/reservations/'.$other->id.'/cancel', ['_token' => 'test-token'])->assertForbidden();
    }

    public function test_student_can_cancel_pending_but_not_in_progress_completed_or_cancelled(): void
    {
        $student = User::factory()->create();
        $pending = Reservation::factory()->create(['user_id' => $student->id, 'status' => 'pending']);
        $inProgress = Reservation::factory()->create(['user_id' => $student->id, 'status' => 'in_progress']);
        $completed = Reservation::factory()->create(['user_id' => $student->id, 'status' => 'completed']);
        $cancelled = Reservation::factory()->create(['user_id' => $student->id, 'status' => 'cancelled']);

        $this->actingAs($student)->withSession(['_token' => 'test-token'])->patch('/student/reservations/'.$pending->id.'/cancel', ['_token' => 'test-token'])->assertRedirect();
        $this->actingAs($student)->withSession(['_token' => 'test-token'])->patch('/student/reservations/'.$inProgress->id.'/cancel', ['_token' => 'test-token'])->assertForbidden();
        $this->actingAs($student)->withSession(['_token' => 'test-token'])->patch('/student/reservations/'.$completed->id.'/cancel', ['_token' => 'test-token'])->assertForbidden();
        $this->actingAs($student)->withSession(['_token' => 'test-token'])->patch('/student/reservations/'.$cancelled->id.'/cancel', ['_token' => 'test-token'])->assertForbidden();
    }

    public function test_staff_can_advance_pending_and_in_progress(): void
    {
        $pending = Reservation::factory()->create(['status' => 'pending']);
        $inProgress = Reservation::factory()->create(['status' => 'in_progress']);

        app(ReservationService::class)->advanceStatus($pending, 'in_progress');
        $this->assertSame('in_progress', $pending->fresh()->status);

        app(ReservationService::class)->advanceStatus($inProgress, 'completed');
        $this->assertSame('completed', $inProgress->fresh()->status);
    }

    public function test_completed_reservation_cannot_be_modified(): void
    {
        $completed = Reservation::factory()->create(['status' => 'completed']);

        $this->expectException(ValidationException::class);
        app(ReservationService::class)->advanceStatus($completed, 'pending');
    }

    public function test_cancelled_reservation_cannot_be_reactivated(): void
    {
        $cancelled = Reservation::factory()->create(['status' => 'cancelled']);

        $this->expectException(ValidationException::class);
        app(ReservationService::class)->advanceStatus($cancelled, 'pending');
    }

    public function test_available_hours_exclude_taken_slots_and_allow_cancelled_slots(): void
    {
        $washer = Washer::factory()->create(['status' => 'available']);
        $date = now()->addDay()->toDateString();
        Reservation::factory()->create(['washing_machine_id' => $washer->id, 'reservation_date' => $date, 'reservation_time' => '08:00', 'status' => 'pending']);
        Reservation::factory()->create(['washing_machine_id' => $washer->id, 'reservation_date' => $date, 'reservation_time' => '09:00', 'status' => 'cancelled']);

        $hours = app(ReservationService::class)->availableHours($washer, $date);

        $this->assertNotContains('08:00', $hours);
        $this->assertContains('09:00', $hours);
    }

    private function assertWasherStatusCannotBeReserved(string $status): void
    {
        $student = User::factory()->create();
        $washer = Washer::factory()->create(['status' => $status]);

        $this->expectException(ValidationException::class);

        app(ReservationService::class)->create($student, [
            'washing_machine_id' => $washer->id,
            'reservation_date' => now()->addDay()->toDateString(),
            'reservation_time' => '11:00',
            'weight_kg' => 5,
        ]);
    }
}
