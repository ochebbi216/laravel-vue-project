<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Reservation;
use App\Models\ReclamationReservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function register(Request $request)
    {
        $phone = $request->input('phone');

        // Check if the phone is exactly 8 digits using regular expression
        if (!preg_match('/^\d{8}$/', $phone)) {
            return response()->json(['error' => 'The phone number must be exactly 8 digits.'], 422);
        }
        $existingUser = User::where('email', $request->input('email'))->first();
        if ($existingUser) {
            return response()->json(['error' => 'The email has already been taken.'], 422);
        }
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'adresse' => $request->adresse
        ]);
        $token = $user->createToken('token-name')->plainTextToken;
        return response()->json([
            $user,
            'status' => true,
            'message' => 'User Created Successfully',
            'token' => $token
        ], 200);
    }


    public function show($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if ($request->has('password')) {
            $request->merge(['password' => bcrypt($request->input('password'))]);
        }
        $user->update($request->all());
        return response()->json($user, 200);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response()->json('User deleted successfully.');
        }
        return response()->json('User not found.', 404);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('token-name')->plainTextToken;
            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $token,
                'user' => $user
            ], 200);
        }
        else{
            return response()->json('The provided user credentials are incorrect.', 400);
        }
        }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required:255',
    //         'password' => 'required',
    //     ]);
    //     if (Auth::guard('user')->attempt($request->only('email', 'password'))) {
    //         $user = Auth::guard('user')->user();
    //         return response()->json(['message' => 'Guest logged in successfully', 'Guest' => $user]);
    //     }

    //     return response()->json(['message' => 'Invalid login credentials'], 401);
    // }

    public function reservationHistory($id)
    {
        $reservations = Reservation::with(['room', 'user'])->withTrashed()->where('id_user', $id)->get();
    
        return response()->json($reservations);
    }
    public function reclamationHistory($id)
    {
        $reclamation = ReclamationReservation::with(['reservation','user'])->where('user_id', $id)->get();
    
        
        return response()->json($reclamation);
    }
    public function addToBlacklist($id)
    {
        // ... Assuming this function will just ban the user ...
        $user = User::find($id);
        if ($user) {
            $user->banned = true;
            $user->save();
            return response()->json('User added to blacklist successfully.');
        }
        return response()->json('User not found.', 404);
    }

    public function removeFromBlacklist($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->banned = false;
            $user->save();
            return response()->json('User removed from blacklist successfully.');
        }
        return response()->json('User not found.', 404);
    }
    public function toggleBanUser($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->banned = !$user->banned;
            $user->save();
            return response()->json(['message' => 'User ban status updated successfully.']);
        }
        return response()->json(['message' => 'User not found.'], 404);
    }
    public function getBlacklistedUsers()
    {
        $bannedUsers = User::where('banned', true)->get();
        return response()->json($bannedUsers);
    }

}