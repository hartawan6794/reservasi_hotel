<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiUserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/v1/user/profile",
     *     summary="Get authenticated user profile",
     *     tags={"User Profile"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="User profile retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="user", type="object")
     *         )
     *     )
     * )
     */
    public function getUserProfile(Request $request)
    {
        return response()->json([
            'status' => true,
            'user' => $request->user()
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/v1/user/profile/update",
     *     summary="Update user profile",
     *     tags={"User Profile"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *                 @OA\Property(property="phone", type="string", example="0812345678"),
     *                 @OA\Property(property="address", type="string", example="123 Street"),
     *                 @OA\Property(property="photo", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profile updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Profile Updated Successfully"),
     *             @OA\Property(property="user", type="object")
     *         )
     *     )
     * )
     */
    public function updateUserProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/user_images/' . $user->photo));
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/user_images'), $filename);
            $user->photo = $filename;
            $user->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'Profile Updated Successfully',
            'user' => $user
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/v1/user/bookings",
     *     summary="Get user bookings",
     *     tags={"User Bookings"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Bookings retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="bookings", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function getUserBookings(Request $request)
    {
        $bookings = Booking::with('room.type')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'bookings' => $bookings
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/v1/user/booking/update/{id}",
     *     summary="Update booking details",
     *     tags={"User Bookings"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Booking ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="check_in", type="string", format="date", example="2024-01-01"),
     *             @OA\Property(property="check_out", type="string", format="date", example="2024-01-05"),
     *             @OA\Property(property="number_of_rooms", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Booking updated successfully"
     *     )
     * )
     */
    public function updateBooking(Request $request, $id)
    {
        $booking = Booking::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$booking) {
            return response()->json([
                'status' => false,
                'message' => 'Booking not found or unauthorized'
            ], 404);
        }

        // Only allow update if payment is not complete and status is pending
        if ($booking->payment_status == '1') {
            return response()->json([
                'status' => false,
                'message' => 'Cannot update booking with completed payment'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'check_in' => 'nullable|date|after_or_equal:today',
            'check_out' => 'nullable|date|after:check_in',
            'number_of_rooms' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Logic here could be more complex (recalculating price)
        // For simplicity and matching the Frontend controller's UpdateBooking:
        if ($request->has(['check_in', 'check_out', 'number_of_rooms'])) {
            $room = $booking->room;
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
        }

        return response()->json([
            'status' => true,
            'message' => 'Booking Updated Successfully',
            'booking' => $booking
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/v1/user/password/update",
     *     summary="Change user password",
     *     tags={"User Profile"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"old_password","new_password","new_password_confirmation"},
     *             @OA\Property(property="old_password", type="string", format="password"),
     *             @OA\Property(property="new_password", type="string", format="password"),
     *             @OA\Property(property="new_password_confirmation", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Password changed successfully")
     * )
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Hash::check($request->old_password, Auth::user()->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Old Password Does not Match!'
            ], 400);
        }

        User::whereId(Auth::id())->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Password Change Successfully'
        ], 200);
    }
}
