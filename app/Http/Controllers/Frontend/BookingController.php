<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingRoomList;
use App\Models\Room;
use App\Models\RoomNumber;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Admin: Display all bookings
     */
    public function BookingList()
    {
        $allData = Booking::with('user', 'room.type')->latest()->get();
        return view('backend.booking.booking_list', compact('allData'));
    }

    /**
     * Admin: View booking details (read-only)
     */
    public function ViewBooking($id)
    {
        $editData = Booking::with('user', 'room.type', 'assign_rooms.room_number')->findOrFail($id);
        return view('backend.booking.view_booking', compact('editData'));
    }

    /**
     * Admin: Edit booking
     */
    public function EditBooking($id)
    {
        $editData = Booking::with('user', 'room.type', 'assign_rooms.room_number')->findOrFail($id);
        
        // Prevent editing if payment is complete
        if ($editData->payment_status == '1') {
            $notification = array(
                'message' => 'Cannot edit booking with completed payment status',
                'alert-type' => 'error'
            );
            return redirect()->route('booking.list')->with($notification);
        }
        
        return view('backend.booking.edit_booking', compact('editData'));
    }

    /**
     * Admin: Download invoice PDF
     */
    public function DownloadInvoice($id)
    {
        $editData = Booking::with('user', 'room.type')->findOrFail($id);
        $pdf = Pdf::loadView('backend.booking.booking_invoice', compact('editData'));
        return $pdf->download('invoice-' . $editData->code . '.pdf');
    }

    /**
     * Admin: Delete booking
     */
    public function DeleteBooking($id)
    {
        $booking = Booking::findOrFail($id);
        
        // Prevent deleting if payment is complete
        if ($booking->payment_status == '1') {
            $notification = array(
                'message' => 'Cannot delete booking with completed payment status',
                'alert-type' => 'error'
            );
            return redirect()->route('booking.list')->with($notification);
        }
        
        // Delete related booking room lists
        \App\Models\BookingRoomList::where('booking_id', $id)->delete();
        
        $booking->delete();

        $notification = array(
            'message' => 'Booking Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('booking.list')->with($notification);
    }

    /**
     * Frontend: Display checkout page
     */
    public function Checkout()
    {
        if (!session('book_date')) {
            return redirect()->route('froom.all')->with('error', 'Please select room and dates first.');
        }

        $book_data = session('book_date');
        $room = Room::with('type')->findOrFail($book_data['room_id']);

        // Calculate nights
        $check_in = new \DateTime($book_data['check_in']);
        $check_out = new \DateTime($book_data['check_out']);
        $nights = $check_in->diff($check_out)->days;

        return view('frontend.checkout.checkout', compact('room', 'book_data', 'nights'));
    }

    /**
     * Frontend: Store booking from room details page
     */
    public function BookingStore(Request $request, $id)
    {

        // dd($request->all());
        // $request->validate([
        //     'check_in' => 'required|date|after_or_equal:today',
        //     'check_out' => 'required|date|after:check_in',
        //     'persion' => 'required|integer|min:1',
        //     'number_of_rooms' => 'required|integer|min:1',
        // ]);

        $room = Room::findOrFail($id);

        // Check if person count is valid
        if ($request->persion > $room->total_adult) {
            return redirect()->back()->with('error', 'Number of persons exceeds room capacity.');
        }

        // Calculate nights
        $check_in = new \DateTime($request->check_in);
        $check_out = new \DateTime($request->check_out);
        $total_nights = $check_in->diff($check_out)->days;

        // Store booking data in session
        $book_data = [
            'room_id' => $id,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'persion' => $request->persion,
            'number_of_rooms' => $request->number_of_rooms,
        ];

        session(['book_date' => $book_data]);

        return redirect()->route('checkout');
    }

    /**
     * Frontend: Store checkout with payment
     */
    public function CheckoutStore(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:255',
            'payment_method' => 'required|in:COD,Stripe',
        ]);
        
        if (!session('book_date')) {
            return redirect()->route('froom.all')->with('error', 'Booking session expired. Please try again.');
        }

        $book_data = session('book_date');
        $room = Room::findOrFail($book_data['room_id']);

        // Calculate prices
        $check_in = new \DateTime($book_data['check_in']);
        $check_out = new \DateTime($book_data['check_out']);
        $total_nights = $check_in->diff($check_out)->days;

        $subtotal = $room->price * $total_nights * $book_data['number_of_rooms'];
        $discount_amount = ($room->discount / 100) * $subtotal;
        $total_price = $subtotal - $discount_amount;

        // Generate booking code
        $code = 'BK' . strtoupper(Str::random(8));

        // Create booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'rooms_id' => $book_data['room_id'],
            'check_in' => $book_data['check_in'],
            'check_out' => $book_data['check_out'],
            'persion' => $book_data['persion'],
            'number_of_rooms' => $book_data['number_of_rooms'],
            'total_night' => $total_nights,
            'actual_price' => $room->price,
            'subtotal' => $subtotal,
            'discount' => $discount_amount,
            'total_price' => $total_price,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_method == 'COD' ? 0 : 1,
            'status' => 0,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'country' => $request->country,
            'address' => $request->address,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'code' => $code,
        ]);

        // Clear session
        session()->forget('book_date');

        // Store booking ID in session for stripe payment
        session(['booking_id' => $booking->id]);

        // Handle Stripe payment if selected
        if ($request->payment_method == 'Stripe') {
            // Redirect to stripe payment page
            return redirect()->route('stripe_pay');
        }

        return redirect()->route('user.booking')->with('success', 'Booking created successfully. Payment pending.');
    }

    /**
     * Frontend: Handle Stripe payment
     */
    public function stripe_pay(Request $request)
    {
        // Get booking from session or latest booking for current user
        $booking = null;
        if (session('booking_id')) {
            $booking = Booking::where('id', session('booking_id'))
                ->where('user_id', Auth::id())
                ->first();
        }
        
        if (!$booking) {
            $booking = Booking::where('user_id', Auth::id())
                ->latest()
                ->first();
        }

        if ($request->isMethod('post') && $request->has('stripeToken')) {
            if (!$booking) {
                return redirect()->route('checkout')->with('error', 'Booking not found.');
            }

            try {
                // Here you would integrate with Stripe API
                // For now, we'll just mark as paid
                $booking->update([
                    'payment_status' => 1,
                    'status' => 1,
                ]);

                session()->forget('booking_id');
                return redirect()->route('user.booking')->with('success', 'Payment successful!');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Payment failed: ' . $e->getMessage());
            }
        }

        if (!$booking) {
            return redirect()->route('checkout')->with('error', 'Booking not found.');
        }

        return view('frontend.checkout.stripe_pay', compact('booking'));
    }

    /**
     * Admin: Update booking status
     */
    public function UpdateBookingStatus(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'nullable|in:0,1',
            'status' => 'nullable|in:0,1',
        ]);

        $booking = Booking::findOrFail($id);
        $booking->update($request->only(['payment_status', 'status']));

        return redirect()->back()->with('success', 'Booking status updated successfully.');
    }

    /**
     * Admin: Update booking details
     */
    public function UpdateBooking(Request $request, $id)
    {
        $request->validate([
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'number_of_rooms' => 'required|integer|min:1',
        ]);

        $booking = Booking::findOrFail($id);
        
        // Prevent updating if payment is complete
        if ($booking->payment_status == '1') {
            $notification = array(
                'message' => 'Cannot update booking with completed payment status',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
        
        $room = Room::findOrFail($booking->rooms_id);

        // Calculate new prices
        $check_in = new \DateTime($request->check_in);
        $check_out = new \DateTime($request->check_out);
        $total_nights = $check_in->diff($check_out)->days;

        $subtotal = $room->price * $total_nights * $request->number_of_rooms;
        $discount_amount = ($room->discount / 100) * $subtotal;
        $total_price = $subtotal - $discount_amount;

        $booking->update([
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'number_of_rooms' => $request->number_of_rooms,
            'total_night' => $total_nights,
            'subtotal' => $subtotal,
            'discount' => $discount_amount,
            'total_price' => $total_price,
        ]);

        return redirect()->back()->with('success', 'Booking updated successfully.');
    }

    /**
     * Admin: Show assign room modal
     */
    public function AssignRoom($id)
    {
        $booking = Booking::with('room')->findOrFail($id);

        // Get available room numbers for this booking
        $room_numbers = $this->getAvailableRoomNumbers($booking->rooms_id, $booking->check_in, $booking->check_out);

        return view('backend.booking.assign_room', compact('booking', 'room_numbers'));
    }

    /**
     * Admin: Store assigned room
     */
    public function AssignRoomStore($booking_id, $room_number_id)
    {
        $booking = Booking::findOrFail($booking_id);
        $room_number = RoomNumber::findOrFail($room_number_id);

        // Check if room number is already assigned to this booking
        $existing = BookingRoomList::where('booking_id', $booking_id)
            ->where('room_number_id', $room_number_id)
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Room number already assigned to this booking.');
        }

        BookingRoomList::create([
            'booking_id' => $booking_id,
            'room_number_id' => $room_number_id,
            'room_id' => $booking->rooms_id,
        ]);

        return redirect()->back()->with('success', 'Room assigned successfully.');
    }

    /**
     * Admin: Delete assigned room
     */
    public function AssignRoomDelete($id)
    {
        $assign_room = BookingRoomList::findOrFail($id);
        $assign_room->delete();

        return redirect()->back()->with('success', 'Assigned room deleted successfully.');
    }

    /**
     * Frontend: User booking list
     */
    public function UserBooking()
    {
        $allData = Booking::with('user', 'room.type')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('frontend.dashboard.user_booking', compact('allData'));
    }

    /**
     * Frontend: User invoice
     */
    public function UserInvoice($id)
    {
        $editData = Booking::with('user', 'room.type')->findOrFail($id);

        // Check if booking belongs to authenticated user
        if ($editData->user_id != Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        return view('backend.booking.booking_invoice', compact('editData'));
    }

    /**
     * Mark notification as read
     */
    public function MarkAsRead($notification)
    {
        $user = Auth::user();
        if ($user) {
            try {
                $notificationModel = DatabaseNotification::where('id', $notification)
                    ->where('notifiable_id', $user->id)
                    ->where('notifiable_type', get_class($user))
                    ->firstOrFail();
                $notificationModel->markAsRead();
            } catch (\Exception $e) {
                // Handle notification not found
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Helper: Get available room numbers
     */
    private function getAvailableRoomNumbers($room_id, $check_in, $check_out)
    {
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
            ->where(function($query) use ($check_in, $check_out) {
                // Proper date overlap logic: bookings overlap if check_in < new_check_out AND check_out > new_check_in
                $query->where('bookings.check_in', '<', $check_out)
                      ->where('bookings.check_out', '>', $check_in);
            })
            ->distinct()
            ->pluck('booking_room_lists.room_number_id')
            ->toArray();

        // Get available room numbers
        $available_room_numbers = array_diff($room_numbers, $booked_room_numbers);

        return RoomNumber::whereIn('id', $available_room_numbers)->get();
    }
}
