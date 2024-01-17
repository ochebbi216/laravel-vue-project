<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;
use App\Rules\CheckInDate;

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
    // public function isAvailable($checkin, $checkout)
    // {
    //     $checkinDate = Carbon::parse($checkin);
    //     $checkoutDate = Carbon::parse($checkout);

    //     $currentDate = Carbon::now();
    //     if( $checkinDate<$currentDate && $currentDate<$checkoutDate){
    //         return false;
    //     }
    //     else {
    //         return true;
    //     }
    // }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_room' => 'required|exists:rooms,id',
            'id_user' => 'required|exists:users,id',
            'checkin' => ['required', 'date', 'after:today', new CheckInDate],
            'checkout' => 'required|date|after:checkin',
            'nbadulte' => 'required|integer:1',
            'nbenfants' => 'required|integer:0',
            'pension' => 'required|in:demi-pension,pension-complete,lpd,all-inclusive',
        ]);
    
        DB::beginTransaction();
        
        try {
            $room = Room::findOrFail($validatedData['id_room']);

            if (!$room->isAvailable($validatedData['checkin'], $validatedData['checkout'])) {
                DB::rollback();
                return response()->json(['message' => 'Room is not available'], 400);
            }
            $capacity = ['Single'=>1,'Double'=>2,'Triple'=>3];
            if ($room->room_type == "Suite" && $validatedData['nbenfants']+$validatedData['nbadulte']<=5){
            }
            else if($validatedData['nbenfants']+$validatedData['nbadulte']<=$capacity[$room->room_type]){   
            }
            else{
                return response()->json(['message' => 'Room capacity not match with Guest number'], 400);
            }
            $reservation = new Reservation($validatedData);
            $reservation->room_type = $room->room_type;
            $totalCost = $this->calculateTotalCost($request,$room->room_type);
            $reservation->total_cost = $totalCost;
            $reservation->save();
    
            DB::commit();
    
            return response()->json(['message' => 'Reservation created successfully', 'reservation' => $reservation], 201);
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            return response()->json(['message' => 'Room not found', 'error' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error("An error occurred while creating reservation: " . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
                'request' => $request->all(),
                'validatedData' => $validatedData
            ]);
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
        // $room = Room::findOrFail($validatedData['id_room']);

        // Directly update the reservation with all request data without validation
        if ($reservation) {
            $reservation->update($request->all());
            // $reservation->total_cost = $this->calculateTotalCost($request,$room->room_type);

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
    private function calculateTotalCost(Request $request ,$type)
    {
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
        ];

        $checkIn = Carbon::parse($request->checkin);
        $checkOut = Carbon::parse($request->checkout);
        $duration = $checkIn->diffInDays($checkOut);
        $dailyRate = $dailyRates[$type][$request->pension];

        return $duration * $dailyRate * $request->nbadulte; // Example calculation for adults; include children calculation if needed
    }
}