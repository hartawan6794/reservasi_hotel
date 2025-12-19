<?php

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

Route::middleware('auth:sanctum')->group(function () {

    // User Profile Routes
    Route::get('/v1/user/profile', 'App\Http\Controllers\Api\ApiUserController@getUserProfile');
    Route::post('/v1/user/profile/update', 'App\Http\Controllers\Api\ApiUserController@updateUserProfile');
    Route::post('/v1/user/password/update', 'App\Http\Controllers\Api\ApiUserController@changePassword');

    // User Booking Routes
    Route::get('/v1/user/bookings', 'App\Http\Controllers\Api\ApiUserController@getUserBookings');
    Route::post('/v1/user/booking/update/{id}', 'App\Http\Controllers\Api\ApiUserController@updateBooking');

    // Authentication Routes (Protected)
    Route::post('/v1/logout', 'App\Http\Controllers\Api\ApiAuthController@logout');

    // Other Protected Routes
    Route::post('/v1/rooms/review', 'App\Http\Controllers\Api\RoomApiController@storeRoomReview');
});

// Public Auth Routes
Route::post('/v1/login', 'App\Http\Controllers\Api\ApiAuthController@login');
Route::post('/v1/register', 'App\Http\Controllers\Api\ApiAuthController@register');

// Public Room Routes (v1)
Route::prefix('v1/rooms')->group(function () {
    Route::get('/', 'App\Http\Controllers\Api\RoomApiController@getAllRooms');
    Route::get('/{id}', 'App\Http\Controllers\Api\RoomApiController@getRoomDetails');
    Route::get('/search/available', 'App\Http\Controllers\Api\RoomApiController@searchRooms');
    Route::get('/search/details/{id}', 'App\Http\Controllers\Api\RoomApiController@getSearchRoomDetails');
    Route::get('/check/availability', 'App\Http\Controllers\Api\RoomApiController@checkRoomAvailability');
});
