<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $attributes = [
        'banned' => false,
    ];
    protected $fillable = [
        'name',
        'email',
        'phone',
        'adresse',
        'password',
        'banned', // true or false
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = substr($value, 0, 8);
    }

    // User Relationships
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
    // User can has many reclamations about his reservations
    public function reclamationReservations()
    {
        return $this->hasMany(ReclamationReservation::class);
    }
    // Add user to the blacklist
    public function addToBlacklist()
    {
        $this->banned = true;
        $this->save();
    }

    // Get User reservation history
    public function reservationHistory()
    {
        return $this->reservations()->get();
    }
}