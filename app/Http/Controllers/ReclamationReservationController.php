<?php

namespace App\Http\Controllers;

use App\Models\ReclamationReservation;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReclamationReservationController extends Controller
{
    public function index()
    {
        $reclamationReservations = ReclamationReservation::with(['user'])->get();
        return response()->json($reclamationReservations, 200);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'user_id' => 'required|exists:users,id',
            'message' => 'required',
        ]);
        $reservation = Reservation::findOrFail($validated['reservation_id']);
        
        // // Assurez-vous que l'utilisateur a le droit de réclamer cette réservation
        // if ($reservation->user_id !== $request->user()->id) {
        //     abort(403);
        // }

        $reclamation = ReclamationReservation::create([
            'reservation_id' => $validated['reservation_id'],
            'user_id' => $validated['user_id'],
            'message' => $validated['message'],
        ]);

        return response()->json($reclamation, 201);
    }

    public function show($id)
    {
        $reclamation = ReclamationReservation::with(['reservation','user'])->find($id);
        if (!$reclamation) {
            return response()->json(['message' => 'Reclamation not found'], 404);
        }
        return response()->json($reclamation);
    }

    public function update(Request $request, $id)
    {
        $reclamation  = ReclamationReservation::find($id);
        $reclamation->status = true;
        $reclamation->update($request->all());
        return response()->json($reclamation);

    }
}