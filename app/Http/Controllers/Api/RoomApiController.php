<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingRoomList;
use App\Models\Facility;
use App\Models\MultiImage;
use App\Models\Room;
use App\Models\RoomNumber;
use App\Models\RoomReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Room Management",
 *     description="APIs for managing and retrieving room information"
 * )
 */
class RoomApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/v1/rooms",
     *     summary="Get All Rooms",
     *     tags={"Room Management"},
     *     @OA\Response(
     *         response=200,
     *         description="Rooms retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Rooms retrieved successfully"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="total", type="integer", example=10)
     *         )
     *     ),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function getAllRooms()
    {
        try {
            $rooms = Room::with('type')
                ->where('status', 1)
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Rooms retrieved successfully',
                'data' => $rooms,
                'total' => $rooms->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve rooms',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/v1/rooms/{id}",
     *     summary="Get Room Details",
     *     tags={"Room Management"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the room",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Room details retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Room not found"),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function getRoomDetails($id)
    {
        try {
            $room = Room::with('type', 'facilities', 'multi_images')->find($id);

            if (!$room) {
                return response()->json([
                    'success' => false,
                    'message' => 'Room not found'
                ], 404);
            }

            $multiImages = MultiImage::where('rooms_id', $id)->get();
            $facilities = Facility::where('rooms_id', $id)->get();
            $otherRooms = Room::where('id', '!=', $id)
                ->where('status', 1)
                ->with('type', 'facilities', 'multi_images')
                ->limit(4)
                ->get();

            // Get approved reviews with average rating
            $reviews = RoomReview::where('room_id', $id)
                ->where('status', 1)
                ->with('user:id,name,photo')
                ->latest()
                ->get();

            $averageRating = $reviews->avg('rating') ?? 0;
            $totalReviews = $reviews->count();

            return response()->json([
                'success' => true,
                'message' => 'Room details retrieved successfully',
                'data' => [
                    'room' => $room,
                    'facilities' => $facilities,
                    'images' => $multiImages,
                    'reviews' => $reviews,
                    'average_rating' => round($averageRating, 1),
                    'total_reviews' => $totalReviews,
                    'other_rooms' => $otherRooms
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve room details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/v1/rooms/search/available",
     *     summary="Search Available Rooms",
     *     description="Search for available rooms based on check-in date, check-out date, and number of persons",
     *     tags={"Room Management"},
     *     @OA\Parameter(name="check_in", in="query", required=true, @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="check_out", in="query", required=true, @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="persion", in="query", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Search completed successfully"),
     *     @OA\Response(response=422, description="Validation failed"),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function searchRooms(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'persion' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $check_in = $request->check_in;
            $check_out = $request->check_out;
            $persion = $request->persion;

            // Calculate total nights
            $check_in_date = new \DateTime($check_in);
            $check_out_date = new \DateTime($check_out);
            $total_nights = $check_in_date->diff($check_out_date)->days;

            // Get all active rooms with active room numbers count
            $rooms = Room::with('type')
                ->withCount([
                    'room_numbers' => function ($query) {
                        $query->where('status', 'Active');
                    }
                ])
                ->where('status', 1)
                ->get();

            // Get booking IDs that overlap with the date range
            $check_date_booking_ids = Booking::where(function ($query) use ($check_in, $check_out) {
                $query->whereBetween('check_in', [$check_in, $check_out])
                    ->orWhereBetween('check_out', [$check_in, $check_out])
                    ->orWhere(function ($q) use ($check_in, $check_out) {
                        $q->where('check_in', '<=', $check_in)
                            ->where('check_out', '>=', $check_out);
                    });
            })
                ->where('status', '!=', 0)
                ->pluck('id')
                ->toArray();

            // Get room availability for each room
            $roomsWithAvailability = $rooms->map(function ($room) use ($check_in, $check_out) {
                $room_numbers = RoomNumber::where('rooms_id', $room->id)
                    ->where('status', 'Active')
                    ->pluck('id')
                    ->toArray();

                $booked_room_numbers = DB::table('booking_room_lists')
                    ->join('bookings', 'booking_room_lists.booking_id', '=', 'bookings.id')
                    ->where('booking_room_lists.room_id', $room->id)
                    ->where('bookings.status', '!=', 0)
                    ->where(function ($query) use ($check_in, $check_out) {
                        $query->where('bookings.check_in', '<', $check_out)
                            ->where('bookings.check_out', '>', $check_in);
                    })
                    ->distinct()
                    ->pluck('booking_room_lists.room_number_id')
                    ->toArray();

                $available_rooms = array_diff($room_numbers, $booked_room_numbers);
                $room->available_count = count($available_rooms);

                return $room;
            });

            return response()->json([
                'success' => true,
                'message' => 'Search completed successfully',
                'data' => [
                    'rooms' => $roomsWithAvailability,
                    'search_params' => [
                        'check_in' => $check_in,
                        'check_out' => $check_out,
                        'persons' => (int) $persion,
                        'total_nights' => $total_nights
                    ],
                    'booked_booking_ids' => $check_date_booking_ids
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/v1/rooms/search/details/{id}",
     *     summary="Get Search Room Details",
     *     description="Retrieve detailed information about a specific room from search results with availability info",
     *     tags={"Room Management"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="check_in", in="query", required=true, @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="check_out", in="query", required=true, @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="persion", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Room details retrieved successfully"),
     *     @OA\Response(response=422, description="Validation failed"),
     *     @OA\Response(response=404, description="Room not found"),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function getSearchRoomDetails(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $room = Room::with('type', 'facilities', 'multi_images')->find($id);

            if (!$room) {
                return response()->json([
                    'success' => false,
                    'message' => 'Room not found'
                ], 404);
            }

            $check_in = $request->get('check_in');
            $check_out = $request->get('check_out');
            $persion = $request->get('persion', 1);

            // Calculate total nights
            $check_in_date = new \DateTime($check_in);
            $check_out_date = new \DateTime($check_out);
            $total_nights = $check_in_date->diff($check_out_date)->days;

            $multiImages = MultiImage::where('rooms_id', $id)->get();
            $facilities = Facility::where('rooms_id', $id)->get();
            $otherRooms = Room::where('id', '!=', $id)
                ->where('status', 1)
                ->with('type')
                ->limit(4)
                ->get();

            // Get approved reviews with average rating
            $reviews = RoomReview::where('room_id', $id)
                ->where('status', 1)
                ->with('user:id,name,photo')
                ->latest()
                ->get();

            $averageRating = $reviews->avg('rating') ?? 0;
            $totalReviews = $reviews->count();

            // Check room availability
            $room_numbers = RoomNumber::where('rooms_id', $id)
                ->where('status', 'Active')
                ->pluck('id')
                ->toArray();

            $booked_room_numbers = DB::table('booking_room_lists')
                ->join('bookings', 'booking_room_lists.booking_id', '=', 'bookings.id')
                ->where('booking_room_lists.room_id', $id)
                ->where('bookings.status', '!=', 0)
                ->where(function ($query) use ($check_in, $check_out) {
                    $query->where('bookings.check_in', '<', $check_out)
                        ->where('bookings.check_out', '>', $check_in);
                })
                ->distinct()
                ->pluck('booking_room_lists.room_number_id')
                ->toArray();

            $available_rooms = array_diff($room_numbers, $booked_room_numbers);
            $available_room_count = count($available_rooms);

            return response()->json([
                'success' => true,
                'message' => 'Room details retrieved successfully',
                'data' => [
                    'room' => $room,
                    'facilities' => $facilities,
                    'images' => $multiImages,
                    'reviews' => $reviews,
                    'average_rating' => round($averageRating, 1),
                    'total_reviews' => $totalReviews,
                    'other_rooms' => $otherRooms,
                    'booking_params' => [
                        'check_in' => $check_in,
                        'check_out' => $check_out,
                        'persons' => (int) $persion,
                        'total_nights' => $total_nights
                    ],
                    'availability' => [
                        'available_rooms' => $available_room_count,
                        'is_available' => $available_room_count > 0
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve room details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/v1/rooms/check/availability",
     *     summary="Check Room Availability",
     *     description="Check the availability of a specific room for given dates",
     *     tags={"Room Management"},
     *     @OA\Parameter(name="room_id", in="query", required=true, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="check_in", in="query", required=true, @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="check_out", in="query", required=true, @OA\Schema(type="string", format="date")),
     *     @OA\Response(response=200, description="Availability checked successfully"),
     *     @OA\Response(response=422, description="Validation failed"),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function checkRoomAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $room_id = $request->room_id;
            $check_in = $request->check_in;
            $check_out = $request->check_out;

            // Get all active room numbers for this room
            $room_numbers = RoomNumber::where('rooms_id', $room_id)
                ->where('status', 'Active')
                ->pluck('id')
                ->toArray();

            // Get booked room numbers for the date range
            $booked_room_numbers = DB::table('booking_room_lists')
                ->join('bookings', 'booking_room_lists.booking_id', '=', 'bookings.id')
                ->where('booking_room_lists.room_id', $room_id)
                ->where('bookings.status', '!=', 0)
                ->where(function ($query) use ($check_in, $check_out) {
                    $query->where('bookings.check_in', '<', $check_out)
                        ->where('bookings.check_out', '>', $check_in);
                })
                ->distinct()
                ->pluck('booking_room_lists.room_number_id')
                ->toArray();

            // Get available room numbers
            $available_rooms = array_diff($room_numbers, $booked_room_numbers);
            $available_room_count = count($available_rooms);

            // Calculate total nights
            $check_in_date = new \DateTime($check_in);
            $check_out_date = new \DateTime($check_out);
            $total_nights = $check_in_date->diff($check_out_date)->days;

            return response()->json([
                'success' => true,
                'message' => 'Availability checked successfully',
                'data' => [
                    'room_id' => (int) $room_id,
                    'available_rooms' => $available_room_count,
                    'is_available' => $available_room_count > 0,
                    'total_nights' => $total_nights,
                    'check_in' => $check_in,
                    'check_out' => $check_out
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check availability',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/v1/rooms/review",
     *     summary="Store Room Review",
     *     description="Submit a review for a specific room. Requires authentication.",
     *     tags={"Room Management"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="room_id", type="integer", example=1),
     *             @OA\Property(property="rating", type="integer", example=5),
     *             @OA\Property(property="comment", type="string", example="Great experience!")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Review submitted successfully"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=422, description="Validation failed"),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function storeRoomReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_id' => 'required|exists:rooms,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();

            \App\Models\RoomReview::create([
                'room_id' => $request->room_id,
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'status' => 0, // Pending approval
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully! It will be published after approval.'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit review',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
