<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',
        'room_type',
        'image',
        'description'
    ];

    // Define the relationship to Reservation
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'id_room');
    }

    // Check room availability
    public function isAvailable($checkin, $checkout)
    {
        $checkinDate = Carbon::parse($checkin);
        $checkoutDate = Carbon::parse($checkout);

        // Check if there are any reservations that conflict with the provided dates
        return !$this->reservations()
            ->where(function ($query) use ($checkinDate, $checkoutDate) {
                $query->whereBetween('checkin', [$checkinDate, $checkoutDate])
                      ->orWhereBetween('checkout', [$checkinDate, $checkoutDate])
                      ->orWhere(function ($query) use ($checkinDate, $checkoutDate) {
                          $query->where('checkin', '<=', $checkinDate)
                                ->where('checkout', '>=', $checkoutDate);
                      });
            })->exists();
    }


    // Accessor to dynamically return the availability
    public function getDisponibleAttribute($value)
    {
        // example logic to check availability for the current date. Modify as needed.
        return $this->isAvailable(Carbon::now(), Carbon::now());
    }
}