<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('washing_machine_id')->constrained('washing_machines')->cascadeOnDelete();
            $table->date('reservation_date');
            $table->string('reservation_time', 5);
            $table->decimal('weight_kg', 5, 2);
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['reservation_date', 'reservation_time']);
        });

        DB::statement("CREATE UNIQUE INDEX reservations_unique_active_slot ON reservations (washing_machine_id, reservation_date, reservation_time) WHERE status <> 'cancelled'");
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS reservations_unique_active_slot');
        Schema::dropIfExists('reservations');
    }
};
