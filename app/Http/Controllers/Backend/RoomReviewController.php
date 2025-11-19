<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\RoomReview;
use Illuminate\Http\Request;

class RoomReviewController extends Controller
{
    /**
     * Display all room reviews (Backend)
     */
    public function AllReview()
    {
        $reviews = RoomReview::with('room.type', 'user')->latest()->get();
        return view('backend.review.all_review', compact('reviews'));
    }

    /**
     * Update review status (Approve/Reject) via AJAX
     */
    public function UpdateReviewStatus(Request $request)
    {
        $request->validate([
            'review_id' => 'required|exists:room_reviews,id',
            'status' => 'required|integer|in:0,1',
        ]);

        $review = RoomReview::findOrFail($request->review_id);
        $review->status = $request->status;
        $review->save();

        $message = $request->status == 1 
            ? 'Review Approved Successfully' 
            : 'Review Rejected Successfully';

        return response()->json([
            'message' => $message,
            'status' => 'success'
        ]);
    }

    /**
     * Delete room review
     */
    public function DeleteReview($id)
    {
        $review = RoomReview::findOrFail($id);
        $review->delete();

        $notification = array(
            'message' => 'Review Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
