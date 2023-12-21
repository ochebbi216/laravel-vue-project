<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_room', 'id_user', 'check_in', 'check_out',
        'number_adults', 'number_children', 'status',
        'type', 'price_adult', 'price_child',
    ];


public static function deleteExpiredReservations()
{
    $expiredReservations = self::where('check_in', '<', now()->toDateString())
        // ->where('status', '!=', 'confirmed') // Optionally, include conditions based on your needs
        ->get();

    foreach ($expiredReservations as $reservation) {
        $reservation->delete();
    }
}

    protected $dates = ['deleted_at'];

    // Relationships
    public function room()
    {
        return $this->belongsTo(Room::class, 'id_room');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Confirm reservation
    public function confirmReservation()
    {
        $this->status = 'confirmed';
        $this->save();
    }
    
    public function reclamationReservations()
    {
        return $this->hasMany(ReclamationReservation::class);
    }
    protected static function booted()
    {
        static::deleted(function ($reservation) {
            // Update the room availability when a reservation is deleted
            $room = $reservation->room;
            if ($room) {
                $room->disponible = true;
                $room->save();
            }
        });
    }
}