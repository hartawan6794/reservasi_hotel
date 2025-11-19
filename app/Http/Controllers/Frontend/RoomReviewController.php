<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\RoomReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomReviewController extends Controller
{
    /**
     * Store a new room review
     */
    public function StoreReview(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10',
        ]);

        RoomReview::create([
            'room_id' => $request->room_id,
            'user_id' => Auth::id(),
            'name' => $request->name,
            'email' => $request->email,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 0, // Pending approval
        ]);

        $notification = array(
            'message' => 'Review submitted successfully! It will be published after approval.',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
