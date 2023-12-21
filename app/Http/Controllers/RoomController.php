<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::all();
        return response()->json($rooms);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    
    $room = new Room($request->only(['room_number', 'room_type', 'disponible', 'image', 'description']));

    $room->save(); // Save the room with the image if provided

    // Return the room data with a 201 Created status
    return response()->json($room, 201);
}
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $room = Room::find($id);
        return response()->json($room);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $room = Room::find($id);
        $room->update($request->all());
        return response()->json($room, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $room = Room::find($id);
        $room->delete();
        return response()->json(null, 204);
    }
}