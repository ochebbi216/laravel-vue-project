<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    public function register(Request $request)
    {
        $existingAdmin = Admin::where('email', $request->input('email'))->first();
        if ($existingAdmin) {
            return response()->json(['error' => 'The email has already been taken.'], 422);
        }
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:6'
        ]);
        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),

        ]);
        $token = $admin->createToken('token-name2')->plainTextToken;
        return response()->json([
            $admin,
            'status' => true,
            'message' => 'Admin Created Successfully',
            'token' => $token
        ], 200);
    }
    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::guard('admin')->attempt($request->only('email', 'password'))) {
        $admin = Auth::guard('admin')->user();
        $token = $admin->createToken('admin-token-name')->plainTextToken;
        return response()->json([
            'status' => true,
            'message' => 'Admin Logged In Successfully',
            'admintoken' => $token,
            'admin' => $admin,
        ], 200);
    }

    throw ValidationException::withMessages([
        'email' => ['The provided credentials are incorrect.'],
    ]);
}
public function logout(Request $request)
{
    $request->user()->tokens()->delete();

    return response()->json([
        'message' => 'Admin successfully logged out'
    ], 200);
}


}

// public function register(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required:255',
    //         'email' => 'required:255:admins',
    //         'password' => 'required:8',
    //     ]);

    //     $admin = Admin::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //     ]);

    //     return response()->json($admin, 201);
    // }

    // // Admin login
    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required:255',
    //         'password' => 'required',
    //     ]);

    //     if (Auth::guard('admin')->attempt($request->only('email', 'password'))) {
    //         $admin = Auth::guard('admin')->user();
    //         return response()->json(['message' => 'Admin logged in successfully', 'admin' => $admin]);
    //     }

    //     return response()->json(['message' => 'Invalid login credentials'], 401);
    // }

    // // Admin logout
    // public function logout(Request $request)
    // {
    //     Auth::guard('admin')->logout();

    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();

    //     return response()->json(['message' => 'Admin logged out successfully']);
    // }