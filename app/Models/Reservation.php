<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\ReservationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    /** @use HasFactory<ReservationFactory> */
    use HasFactory;

    public const ACTIVE_STATUSES = ['pending', 'in_progress'];
    public const STATUSES = ['pending', 'in_progress', 'completed', 'cancelled'];
    public const HOURS = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00'];

    protected $fillable = ['user_id', 'washing_machine_id', 'reservation_date', 'reservation_time', 'garments_count', 'status', 'notes', 'cancelled_at'];

    protected function casts(): array
    {
        return [
            'reservation_date' => 'date',
            'garments_count' => 'integer',
            'cancelled_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function washer(): BelongsTo
    {
        return $this->belongsTo(Washer::class, 'washing_machine_id');
    }

    public function isActive(): bool
    {
        return in_array($this->status, self::ACTIVE_STATUSES, true);
    }

    public function startsAt(): Carbon
    {
        return Carbon::parse($this->reservation_date->toDateString().' '.$this->reservation_time);
    }

    public function isPastSlot(): bool
    {
        return $this->startsAt()->isPast();
    }
}
