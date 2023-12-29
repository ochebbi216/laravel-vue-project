<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        return response()->json($rooms);
    }

    public function store(Request $request)
    {
        // (Implement validation logic)

        $room = new Room($request->only(['room_number', 'room_type', 'image', 'description']));

        // Check room availability
        if (!$room->isAvailable($request->checkin, $request->checkout)) {
            return response()->json(['message' => 'Room is not available for the selected dates'], 409);
        }

        $room->save();
        return response()->json($room, 201);
    }
    public function checkAvailability(Request $request)
    {
        $checkIn = $request->checkin;
        $checkOut = $request->checkout;
        $roomType = $request->room_type;

        // Logic to get available rooms based on check-in, check-out, and room type
        $availableRooms = Room::where('room_type', $roomType)
            ->whereDoesntHave('reservations', function ($query) use ($checkIn, $checkOut) {
                $query->where(function ($q) use ($checkIn, $checkOut) {
                    $q->whereBetween('checkin', [$checkIn, $checkOut])
                        ->orWhereBetween('checkout', [$checkIn, $checkOut])
                        ->orWhere(function ($q) use ($checkIn, $checkOut) {
                            $q->where('checkin', '<=', $checkIn)
                                ->where('checkout', '>=', $checkOut);
                        });
                });
            })
            ->get();

        return response()->json(['available_rooms' => $availableRooms]);
    }
    public function getRoomByType($type){
        $rooms = Room::where('room_type', $type)->get();
    
        if ($rooms->isEmpty()) {
            return response()->json(['message' => 'No such type of rooms exist']);
        }
    
        return response()->json($rooms);
    }

    public function show($id)
    {
        $room = Room::findOrFail($id);
        return response()->json($room);
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);
        
        // ... Validation logic ...

        // Check room availability
        if (!$room->isAvailable($request->checkin, $request->checkout)) {
            return response()->json(['message' => 'Room is not available for the selected dates'], 409);
        }

        $room->update($request->all());
        return response()->json($room, 200);
    }

    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();
        return response()->json(null, 204);
    }
}