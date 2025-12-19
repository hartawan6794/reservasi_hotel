<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingRoomList;
use App\Models\Facility;
use App\Models\MultiImage;
use App\Models\Room;
use App\Models\RoomNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FrontendRoomController extends Controller
{
    /**
     * Display all rooms for frontend
     */
    public function AllFrontendRoomList()
    {
        $rooms = Room::with('type')->where('status', 1)->latest()->get();
        return view('frontend.room.all_rooms', compact('rooms'));
    }

    /**
     * Display room details page
     */
    public function RoomDetailsPage($id)
    {
        $roomdetails = Room::with('type', 'facilities', 'multi_images')->findOrFail($id);
        $multiImage = MultiImage::where('rooms_id', $id)->get();
        $facility = Facility::where('rooms_id', $id)->get();
        $otherRooms = Room::where('id', '!=', $id)->with('type', 'facilities', 'multi_images')->get();

        // Get approved reviews with average rating
        $reviews = \App\Models\RoomReview::where('room_id', $id)
            ->where('status', 1)
            ->with('user')
            ->latest()
            ->get();

        $averageRating = $reviews->avg('rating') ?? 0;
        $totalReviews = $reviews->count();

        return view('frontend.room.room_details', compact('roomdetails', 'multiImage', 'facility', 'otherRooms', 'reviews', 'averageRating', 'totalReviews'));
    }

    /**
     * Search rooms based on check-in, check-out, and number of persons
     */
    public function BookingSearch(Request $request)
    {

        // dd($request->all());
        // $request->validate([
        //     'check_in' => 'required|date',
        //     'check_out' => 'required|date|after:check_in',
        //     'persion' => 'required|integer|min:1',
        // ]);

        $check_in = $request->check_in;
        $check_out = $request->check_out;
        $persion = $request->persion;

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
            ->where('status', '!=', 0) // Exclude cancelled bookings
            ->pluck('id')
            ->toArray();

        return view('frontend.room.search_room', compact('rooms', 'check_date_booking_ids', 'check_in', 'check_out', 'persion'));
    }

    /**
     * Display room details from search results
     */
    public function SearchRoomDetails(Request $request, $id)
    {
        $roomdetails = Room::with('type', 'facilities', 'multi_images')->findOrFail($id);
        $multiImage = MultiImage::where('rooms_id', $id)->get();
        $facility = Facility::where('rooms_id', $id)->get();
        $otherRooms = Room::where('id', '!=', $id)->with('type', 'facilities', 'multi_images')->get();

        // Get approved reviews with average rating
        $reviews = \App\Models\RoomReview::where('room_id', $id)
            ->where('status', 1)
            ->with('user')
            ->latest()
            ->get();

        $averageRating = $reviews->avg('rating') ?? 0;
        $totalReviews = $reviews->count();

        // Get check_in, check_out, persion from query string
        $check_in = $request->get('check_in');
        $check_out = $request->get('check_out');
        $persion = $request->get('persion');
        $room_id = $id;

        return view('frontend.room.search_room_details', compact('roomdetails', 'multiImage', 'facility', 'otherRooms', 'reviews', 'averageRating', 'totalReviews', 'check_in', 'check_out', 'persion', 'room_id'));
    }

    /**
     * Check room availability via AJAX
     */
    public function CheckRoomAvailability(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        $room_id = $request->room_id;
        $check_in = $request->check_in;
        $check_out = $request->check_out;

        // Get all active room numbers for this room
        $room_numbers = RoomNumber::where('rooms_id', $room_id)
            ->where('status', 'Active')
            ->pluck('id')
            ->toArray();

        // Get booked room numbers for the date range
        // Improved logic: Proper date overlap checking
        // A booking overlaps if: check_in < new_check_out AND check_out > new_check_in
        $booked_room_numbers = DB::table('booking_room_lists')
            ->join('bookings', 'booking_room_lists.booking_id', '=', 'bookings.id')
            ->where('booking_room_lists.room_id', $room_id)
            ->where('bookings.status', '!=', 0) // Exclude cancelled bookings
            ->where(function ($query) use ($check_in, $check_out) {
                // Proper date overlap logic: bookings overlap if check_in < new_check_out AND check_out > new_check_in
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
            'available_room' => $available_room_count,
            'total_nights' => $total_nights,
        ]);
    }


}
