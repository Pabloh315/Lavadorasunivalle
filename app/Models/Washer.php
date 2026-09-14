<?php

namespace App\Models;

use Database\Factories\WasherFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Washer extends Model
{
    /** @use HasFactory<WasherFactory> */
    use HasFactory;

    public const STATUSES = ['available', 'maintenance', 'out_of_service'];

    protected $table = 'washing_machines';

    protected $fillable = ['code', 'name', 'status'];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'washing_machine_id');
    }

    public function isReservable(): bool
    {
        return $this->status === 'available';
    }
}
