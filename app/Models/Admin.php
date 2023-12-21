<?php

namespace App\Models; // Cela doit être la première chose après l'ouverture de la balise PHP

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens,HasFactory, Notifiable;

    // Reste du code…

    protected $fillable = [
        'name',
        'email', 
        'password', // supposons que l'adresse et le numéro de téléphone ne sont pas nécessaires pour les admins
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relations administrateur spécifiques
    public function manageReservations()
    {
        // Logique de gestion des réservations
    }

    // On peut potentiellement ajouter plus de fonctions spécifiques à l'admin ici
}