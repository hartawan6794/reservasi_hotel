<?php

use App\Http\Controllers\Api\RoomApiController;
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
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Store room review
    Route::post('/v1/rooms/review', [RoomApiController::class, 'storeRoomReview']);
});


/*
|--------------------------------------------------------------------------
| Room API Routes (v1)
|--------------------------------------------------------------------------
|
| Public API endpoints for room management
| Base URL: /api/v1/rooms
|
*/
Route::prefix('v1')->group(function () {

    Route::prefix('rooms')->group(function () {

        // Get all rooms
        Route::get('/', [RoomApiController::class, 'getAllRooms']);

        // Get room details
        Route::get('/{id}', [RoomApiController::class, 'getRoomDetails']);

        // Search available rooms
        Route::get('/search/available', [RoomApiController::class, 'searchRooms']);

        // Get search room details with availability
        Route::get('/search/details/{id}', [RoomApiController::class, 'getSearchRoomDetails']);

        // Check room availability
        Route::get('/check/availability', [RoomApiController::class, 'checkRoomAvailability']);

    });

});

