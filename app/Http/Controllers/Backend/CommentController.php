<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display all comments (Backend)
     */
    public function AllComment()
    {
        $allcomment = Comment::with('user', 'post')->latest()->get();
        return view('backend.comment.all_comment', compact('allcomment'));
    }

    /**
     * Update comment status (Backend)
     */
    public function UpdateCommentStatus(Request $request)
    {
        $request->validate([
            'comment_id' => 'required|exists:comments,id',
            'is_checked' => 'required|integer|in:0,1',
        ]);

        $comment = Comment::findOrFail($request->comment_id);
        $comment->status = $request->is_checked;
        $comment->save();

        $message = $request->is_checked == 1 
            ? 'Comment Approved Successfully' 
            : 'Comment Disapproved Successfully';

        return response()->json([
            'message' => $message,
            'status' => 'success'
        ]);
    }

    /**
     * Store comment from frontend
     */
    public function StoreComment(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:blog_posts,id',
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        Comment::create([
            'user_id' => $request->user_id,
            'post_id' => $request->post_id,
            'message' => $request->message,
            'status' => 0, // Default status is 0 (pending approval)
        ]);

        $notification = array(
            'message' => 'Comment Added Successfully. Waiting for Approval',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}