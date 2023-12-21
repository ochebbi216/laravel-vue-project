<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',
        'room_type', // single, A2, A3, A4, suite
        'disponible', // boolean: true or false
        'image',
        'description'
    ];
    protected static function booted()
    {
        // Define the event listener for the 'deleted' event
        static::deleted(function ($reservation) {
            // Check if the reservation has a room associated with it
            if ($reservation->room) {
                // Make the room available again
                $reservation->room->disponible = true;
                $reservation->room->save();
            }
        });
    }
    // Room Relationships
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'id_room');
    }

    // Check room availability
    public function isAvailable()
    {
        return $this->disponible;
    }
    // Update room details
    public function updateDetails($details)
    {
        $this->fill($details);
        $this->save();
    }
}

