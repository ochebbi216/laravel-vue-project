<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_room', 
        'id_user',
        'checkin',
        'checkout',
        'nbadulte',
        'nbenfants',
        'status',
        'room_type',
        'pension',
        'total_cost', // Assurez-vous que ce champ existe dans votre table réservations
    ];

    /**
     * Les attributs nécessitant une conversion de type.
     *
     * @var array
     */
    protected $casts = [
        'nbadulte' => 'integer',
        'nbenfants' => 'integer',
        'total_cost' => 'float'
    ];
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
    public static function deleteExpiredReservations()
    {
        $dateLimit = now()->subDays(15)->toDateString();
    
        $expiredReservations = self::where('checkout', '<', $dateLimit)
            // ->where('status', '!=', 'confirmed') // Optionally, include conditions based on your needs
            ->get();
    
        foreach ($expiredReservations as $reservation) {
            $reservation->delete();
        }
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
    /**
     * Calculez la durée du séjour en jours.
     */
    public function getStayDurationAttribute()
    {
        return Carbon::parse($this->attributes['checkin'])
            ->diffInDays(Carbon::parse($this->attributes['checkout']), false);
    }

    /**
     * Définit une mutateur pour le champ checkin.
     */
    public function setCheckinAttribute($value)
    {
        $this->attributes['checkin'] = Carbon::createFromFormat('Y-m-d', $value);
    }

    /**
     * Définit une mutateur pour le champ checkout.
     */
    public function setCheckoutAttribute($value)
    {
        $this->attributes['checkout'] = Carbon::createFromFormat('Y-m-d', $value);
    }
}