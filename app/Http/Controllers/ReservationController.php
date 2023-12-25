<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;

class ReservationController extends Controller
{
    /**
     * Display a listing of all reservations.
     */
    public function index()
    {
        $reservations = Reservation::with(['room', 'user'])->withTrashed()->get();

        return response()->json($reservations);
    }
    /**
     * Store a newly created reservation in storage.
     */
    public function isAvailable($checkin, $checkout,$id_room)
    {
        $reservation = Reservation::find($id_room);
            // Convert to Carbon instances to use Carbon's comparison methods
        $checkinDate = Carbon::parse($checkin);
        $checkoutDate = Carbon::parse($checkout);

        // Current date and time
        $currentDate = Carbon::now();
        if($reservation && $checkinDate<$currentDate && $currentDate<$checkoutDate){
            return false;
        }
        else {
            return true;
        }
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_room' => 'required|exists:rooms,id',
            'id_user' => 'required|exists:users,id',
            'checkin' => 'required|date',
            'checkout' => 'required|date|after:checkin',
            'nbadulte' => 'required|integer:1',
            'nbenfants' => 'required|integer:0',
            'room_type' => 'required|in:Single,Double,Triple,Suite',
            'pension' => 'required|in:demi-pension,pension-complete,lpd,all-inclusive',
        ]);

        DB::beginTransaction();

        try {
            $isAvailable = $this->isAvailable($validatedData['checkin'],$validatedData['checkout'],$validatedData['id_room']);
            if (!$isAvailable) {
                return response()->json(['message' => 'Room is not available'], 400);
            }

            $reservation = new Reservation($validatedData);
            $reservation->total_cost = $this->calculateTotalCost($request);
            $reservation->save();

            DB::commit();

            return response()->json(['message' => 'Reservation created successfully', 'reservation' => $reservation], 201);
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            return response()->json(['message' => 'Room not found with provided ID', 'error' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Failed to create reservation', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified reservation.
     */
    public function show($id)
    {
        $reservation = Reservation::find($id);
        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }
        return response()->json($reservation);
    }

    /**
     * Update the specified reservation in storage.
     */
    // public function update(Request $request, $id)
    // {
    //     $reservation = Reservation::find($id);
    //     if (!$reservation) {
    //         return response()->json(['message' => 'Reservation not found'], 404);
    //     }
    
    //     $validatedData = $request->validate([
    //         'id_room' => 'required|exists:rooms,id',
    //         'id_user' => 'required|exists:users,id',
    //         'checkin' => 'required|date',
    //         'checkout' => 'required|date|after:checkin',
    //         'nbadulte' => 'required|integer',
    //         'nbenfants' => 'required|integer',
    //         'status' =>'required',
    //         'room_type' => 'required|in:Single,Double,Triple,Suite',
    //         'pension' => 'required|in:demi-pension,pension-complete,lpd,all-inclusive',
    //     ]);
    
    //     DB::beginTransaction();
    
    //     try {
    
    //         $reservation->update($validatedData);
    //         $reservation->total_cost = $this->calculateTotalCost($request);
    //         $reservation->save();
    
    //         DB::commit();
    
    //         return response()->json(['message' => 'Reservation updated successfully', 'reservation' => $reservation], 200);
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         return response()->json(['message' => 'Failed to update reservation', 'error' => $e->getMessage()], 500);
    //     }
    // }
    public function update(Request $request, $id)
    {
        $reservation = Reservation::find($id);
        
        // Directly update the reservation with all request data without validation
        if ($reservation) {
            $reservation->update($request->all());
            $reservation->total_cost = $this->calculateTotalCost($request);

            return response()->json($reservation, 200);
        }
    
        return response()->json('Reservation not found.', 404);
    }
    /**
     * Remove the specified reservation from storage.
     */
    public function destroy($id)
    {
        $reservation = Reservation::find($id);
        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }
        
        $reservation->delete();
        return response()->json(['message' => 'Reservation deleted'], 200);
    }

    /**
     * Calculate the total cost of a reservation.
     */
    private function calculateTotalCost(Request $request)
    {
        // Dummy implementation - replace with your actual cost calculation logic
        $dailyRates = [
            'Single' => [
                'demi-pension' => 100,
                'pension-complete' => 150,
                'lpd' => 80,
                'all-inclusive' => 200,
            ],
            'Double' => [
                'demi-pension' => 120,
                'pension-complete' => 170,
                'lpd' => 100,
                'all-inclusive' => 220,
            ],
            'Triple' => [
                'demi-pension' => 140,
                'pension-complete' => 190,
                'lpd' => 120,
                'all-inclusive' => 240,
            ],
            'Suite' => [
                'demi-pension' => 180,
                'pension-complete' => 230,
                'lpd' => 160,
                'all-inclusive' => 300,
            ],
            // other room type rates
        ];

        $checkIn = Carbon::parse($request->checkin);
        $checkOut = Carbon::parse($request->checkout);
        $duration = $checkIn->diffInDays($checkOut);
        $dailyRate = $dailyRates[$request->room_type][$request->pension];

        return $duration * $dailyRate * $request->nbadulte; // Example calculation for adults; include children calculation if needed
    }
}