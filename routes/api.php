<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReclamationReservationController;
use App\Http\Controllers\PaymentController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('api')->group(function () {
    Route::resource('users', UserController::class);
    });

Route::middleware('api')->group(function () {
    Route::resource('rooms', RoomController::class);

    });
Route::get('/getroomsbycat/{type}',[RoomController::class, 'getRoomByType']);
Route::get('/checkAvailability',[RoomController::class,'checkAvailability']);

Route::middleware('api')->group(function () {
    Route::resource('reservations', ReservationController::class);
    });

Route::get('/reservationHistory/{id}', [UserController::class, 'reservationHistory']);
Route::get('/reclamationHistory/{id}', [UserController::class, 'reclamationHistory']);

Route::middleware('api')->group(function($router) {

    Route::post('/createpayment', [PaymentController::class,'createPaymentIntent']);
    
    });

Route::post('/admin/register', [AdminController::class, 'register']);
Route::post('/admin/login', [AdminController::class, 'login']);
Route::middleware('auth:sanctum')->post('/admin/logout', [AdminController::class, 'logout']);

Route::post('/user/register', [UserController::class, 'register']);
Route::post('/user/login', [UserController::class, 'login']);
Route::middleware('auth:sanctum')->post('/user/logout', [UserController::class, 'logout']);

Route::post('/reclamation', [ReclamationReservationController::class, 'store']);
Route::get('/reclamation', [ReclamationReservationController::class, 'index']);
Route::get('/reclamations/{id}', [ReclamationReservationController::class, 'show']);
Route::put('/reclamations/{id}', [ReclamationReservationController::class, 'update']);
Route::put('/users/{id}/toggle-ban', [UserController::class, 'toggleBanUser']);
