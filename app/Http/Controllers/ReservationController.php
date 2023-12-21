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
    public function index()
    {
        $reservations = Reservation::with(['room', 'user'])->withTrashed()->get();

        return response()->json($reservations);
    }

    public function show($id)
    {
        $reservation = Reservation::find($id);
        return response()->json($reservation);
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::find($id);
        
        // You probably want to validate the update input here as well
        if ($reservation) {
            $reservation->update($request->all());
            return response()->json($reservation, 200);
        }

        return response()->json('Reservation not found.', 404);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_room' => 'required|exists:rooms,id',
            'id_user' => 'required|exists:users,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'number_adults' => 'required|integer:1',
            'number_children' => 'required|integer:0',
            'type' => 'requiredin:demi-pension,pension-complète,Lpd,tout inclus',
        ]);

        DB::beginTransaction();

        try {
            $room = Room::findOrFail($validatedData['id_room']);
            if (!$room->disponible) {
                return response()->json(['message' => 'Room is not available'], 400);
            }
            
            $totalPrice = $this->calculatePrice($room, $validatedData);

            $room->disponible = false;
            $room->save();

            $reservationData = array_merge($validatedData, ['total_price' => $totalPrice]);
            $reservation = new Reservation($reservationData);
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

    private function calculatePrice(Room $room, array $validatedData)
    {
        $days = Carbon::parse($validatedData['check_in'])->diffInDays(Carbon::parse($validatedData['check_out']));

        $pricePerAdultDay = $this->getDailyPricePerAdult($room, $validatedData['type']);
        $pricePerChildDay = $this->getDailyPricePerChild($room, $validatedData['type']);

        $totalPrice = ($validatedData['number_adults'] * $pricePerAdultDay + $validatedData['number_children'] * $pricePerChildDay) * $days;

        return $totalPrice;
    }

    private function getDailyPricePerAdult(Room $room, $pensionType)
    {
        // Dummy logic - replace this with actual price lookup based on room and pension type
        $baseAdultPrice = 100; // Assume a base price
        switch ($pensionType) {
            case 'demi-pension':
                return $baseAdultPrice * 1.1; // 10% more for demi-pension
            case 'pension-complète':
                return $baseAdultPrice * 1.5; // 50% more for pension-complète
            case 'Lpd': // Assuming 'Lpd' is some form of limited pension deal
                return $baseAdultPrice * 1.2;
            case 'tout inclus':
                return $baseAdultPrice * 2; // Double price for all-inclusive
        }
        
        return $baseAdultPrice;
    }

    private function getDailyPricePerChild(Room $room, $pensionType)
    {
        // Children pricing might be a percentage of the adult pricing
        $percentageOfAdultPrice = 0.5; // Assume children are half the price of adults
        
        return $this->getDailyPricePerAdult($room, $pensionType) * $percentageOfAdultPrice;
    }
    // public function store(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'id_room' => 'required|exists:rooms,id',  // Correction: Added and 'exists:' corrected
    //         'id_user' => 'required|exists:users,id',  // Same as above
    //         'check_in' => 'required|date',            // Correction: Added'
    //         'check_out' => 'required|date|after:check_in', // Correction:after:'
    //         'total_numbers' => 'required',
    //         'type'=> 'required',
    //         'payment' =>'required'
    //     ]);

    //     DB::beginTransaction();

    //     try {
    //         $room = Room::findOrFail($validatedData['id_room']);
    //         if (!$room->disponible) {
    //             return response()->json(['message' => 'Room is not available'], 400);
    //         }
    //         $room->disponible = false;
    //         $room->save();

    //         $reservation = new Reservation($validatedData);
    //         $reservation->save();

    //         DB::commit();

    //         return response()->json(['message' => 'Reservation created successfully', 'reservation' => $reservation], 201);
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         return response()->json(['message' => 'Failed to create reservation', 'error' => $e->getMessage()], 500);
    //     }
    // }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $reservation = Reservation::with('room')->findOrFail($id);
            $room = $reservation->room;
            $room->disponible = true;
            $room->save();
            $reservation->delete();
            DB::commit();

            return response()->json(['message' => 'Reservation and room availability updated successfully'], 200);
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            return response()->json(['message' => 'No reservation found with provided ID'], 404);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Failed to delete reservation', 'error' => $e->getMessage()], 500);
        }
    }

    // Add any other methods you may need...
}
// <?php

// namespace App\Http\Controllers;

// use App\Models\Reservation;
// use Illuminate\Http\Request;

// class ReservationController extends Controller
// {
//     public function index()
//     {
//         $reservations = Reservation::with(['room', 'user'])->get();        
//         return response()->json($reservations);
//     }

//     public function store(Request $request)
//     {
//         // Directly creating a reservation with all request data without validation
//         $reservation = new Reservation($request->all());
//         $reservation->save();
    
//         return response()->json($reservation, 201);
//     }

//     public function show($id)
//     {
//         $reservation = Reservation::find($id);
//         return response()->json($reservation);
//     }

//     public function update(Request $request, $id)
//     {
//         $reservation = Reservation::find($id);
        
//         // Directly update the reservation with all request data without validation
//         if ($reservation) {
//             $reservation->update($request->all());
//             return response()->json($reservation, 200);
//         }
    
//         return response()->json('Reservation not found.', 404);
//     }
//     public function destroy($id)
//     {
        
//         $reservation = Reservation::find($id);
//         if($reservation) {

//             $reservation->delete();
//             return response()->json('Reservation deleted successfully.');
//         }
//         return response()->json('Reservation not found.', 404);
//     }

// }