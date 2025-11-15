<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingRoomList;
use App\Models\Room;
use App\Models\RoomNumber;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomListController extends Controller
{
    /**
     * View Room List with booking information
     * Improved: Shows current active booking and upcoming bookings
     */
    public function ViewRoomList()
    {
        // Get current date for filtering active bookings
        $today = date('Y-m-d');
        
        // Get all room numbers with their bookings using eager loading for better performance
        $room_number_list = RoomNumber::with([
            'room_type:id,name'
        ])->get()->map(function($room_number) use ($today) {
            // Get current active booking (check-in <= today AND check-out >= today)
            $current_booking = BookingRoomList::where('room_number_id', $room_number->id)
                ->whereHas('booking', function($query) use ($today) {
                    $query->where('status', '!=', 0) // Exclude cancelled
                          ->where('check_in', '<=', $today)
                          ->where('check_out', '>=', $today);
                })
                ->with(['booking' => function($query) {
                    $query->select('id', 'code', 'name', 'check_in', 'check_out', 'status', 'payment_status');
                }])
                ->first();
            
            // Get upcoming bookings (check-in > today)
            $upcoming_bookings = BookingRoomList::where('room_number_id', $room_number->id)
                ->whereHas('booking', function($query) use ($today) {
                    $query->where('status', '!=', 0)
                          ->where('check_in', '>', $today);
                })
                ->with(['booking' => function($query) {
                    $query->select('id', 'code', 'name', 'check_in', 'check_out', 'status', 'payment_status')
                          ->orderBy('check_in', 'asc');
                }])
                ->get();
            
            // Add dynamic properties using setAttribute
            if ($current_booking && $current_booking->booking) {
                $room_number->setAttribute('current_booking', (object)[
                    'booking_id' => $current_booking->booking->id,
                    'booking_no' => $current_booking->booking->code,
                    'customer_name' => $current_booking->booking->name,
                    'check_in' => $current_booking->booking->check_in,
                    'check_out' => $current_booking->booking->check_out,
                    'booking_status' => $current_booking->booking->status,
                    'payment_status' => $current_booking->booking->payment_status,
                ]);
            } else {
                $room_number->setAttribute('current_booking', null);
            }
            
            $room_number->setAttribute('upcoming_bookings', $upcoming_bookings);
            $room_number->setAttribute('is_available', $current_booking === null);
            
            return $room_number;
        });

        return view('backend.allroom.roomlist.view_roomlist', compact('room_number_list'));
    }

    /**
     * Show form to add new room booking
     */
    public function AddRoomList()
    {
        // Get room types with their associated rooms
        $roomtype = RoomType::with('room')->get()->filter(function($item) {
            return $item->room !== null;
        });
        
        return view('backend.allroom.roomlist.add_roomlist', compact('roomtype'));
    }

    /**
     * Store new room booking
     */
    public function StoreRoomList(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'number_of_rooms' => 'required|integer|min:1',
            'number_of_person' => 'required|string',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        // Get room information
        $room = Room::findOrFail($validated['room_id']);
        
        // Calculate total nights
        $check_in = new \DateTime($validated['check_in']);
        $check_out = new \DateTime($validated['check_out']);
        $total_night = $check_in->diff($check_out)->days;

        // Get available room numbers for the selected dates
        $available_rooms = $this->getAvailableRooms($validated['room_id'], $validated['check_in'], $validated['check_out'], $validated['number_of_rooms']);

        if (count($available_rooms) < $validated['number_of_rooms']) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Not enough available rooms for the selected dates.');
        }

        // Generate booking code
        $booking_code = 'BK' . date('Ymd') . rand(1000, 9999);

        // Create booking
        $booking = Booking::create([
            'rooms_id' => $validated['room_id'],
            'user_id' => auth()->id(),
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'persion' => $validated['number_of_person'],
            'number_of_rooms' => $validated['number_of_rooms'],
            'total_night' => $total_night,
            'actual_price' => $room->price ?? 0,
            'subtotal' => ($room->price ?? 0) * $total_night * $validated['number_of_rooms'],
            'discount' => 0,
            'total_price' => ($room->price ?? 0) * $total_night * $validated['number_of_rooms'],
            'payment_method' => 'cash',
            'payment_status' => 'pending',
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'country' => $validated['country'] ?? null,
            'state' => $validated['state'] ?? null,
            'zip_code' => $validated['zip_code'] ?? null,
            'address' => $validated['address'] ?? null,
            'code' => $booking_code,
            'status' => 1,
        ]);

        // Assign rooms to booking
        foreach (array_slice($available_rooms, 0, $validated['number_of_rooms']) as $room_number_id) {
            BookingRoomList::create([
                'booking_id' => $booking->id,
                'room_id' => $validated['room_id'],
                'room_number_id' => $room_number_id,
            ]);
        }

        return redirect()->route('view.room.list')
            ->with('success', 'Room booking created successfully. Booking Code: ' . $booking_code);
    }

    /**
     * Get available room numbers for given dates
     * Improved logic with proper date overlap checking
     */
    private function getAvailableRooms($room_id, $check_in, $check_out, $number_of_rooms)
    {
        // Get all active room numbers for this room
        $room_numbers = RoomNumber::where('rooms_id', $room_id)
            ->where('status', 'Active')
            ->pluck('id')
            ->toArray();

        if (empty($room_numbers)) {
            return [];
        }

        // Get booked room numbers for the date range
        // Logic: A booking overlaps if:
        // - check_in < new_check_out AND check_out > new_check_in
        // This covers all overlap scenarios
        $booked_room_numbers = DB::table('booking_room_lists')
            ->join('bookings', 'booking_room_lists.booking_id', '=', 'bookings.id')
            ->where('booking_room_lists.room_id', $room_id)
            ->where('bookings.status', '!=', 0) // Exclude cancelled bookings
            ->where(function($query) use ($check_in, $check_out) {
                // Proper date overlap logic: bookings overlap if check_in < new_check_out AND check_out > new_check_in
                $query->where('bookings.check_in', '<', $check_out)
                      ->where('bookings.check_out', '>', $check_in);
            })
            ->distinct()
            ->pluck('booking_room_lists.room_number_id')
            ->toArray();

        // Get available room numbers (rooms not in booked list)
        $available_rooms = array_diff($room_numbers, $booked_room_numbers);

        return array_values($available_rooms);
    }
}
