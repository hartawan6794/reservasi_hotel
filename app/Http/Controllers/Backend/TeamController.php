<?php

namespace App\Http\Controllers\Backend;

use App\Models\BookArea;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;

class TeamController extends Controller
{
    /**
     * Display all team members.
     */
    public function AllTeam()
    {
        $team = Team::latest()->get();
        return view('backend.team.all_team', compact('team'));
    }

    /**
     * Show the add team form.
     */
    public function AddTeam()
    {
        return view('backend.team.add_team');
    }

    /**
     * Store a new team member.
     */
    public function StoreTeam(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'postion' => 'required|string|max:255',
            'facebook' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('upload/team'), $name_gen);
            $data['image'] = 'upload/team/' . $name_gen;
        }

        Team::create($data);

        $notification = array(
            'message' => 'Team Member Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.team')->with($notification);
    }

    /**
     * Show the edit form for a team member.
     */
    public function EditTeam($id)
    {
        $team = Team::findOrFail($id);
        return view('backend.team.edit_team', compact('team'));
    }

    /**
     * Update an existing team member.
     */
    public function UpdateTeam(Request $request)
    {
        $team = Team::findOrFail($request->id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'postion' => 'required|string|max:255',
            'facebook' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($team->image && File::exists(public_path($team->image))) {
                File::delete(public_path($team->image));
            }

            $image = $request->file('image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('upload/team'), $name_gen);
            $data['image'] = 'upload/team/' . $name_gen;
        } else {
            $data['image'] = $team->image;
        }

        $team->update($data);

        $notification = array(
            'message' => 'Team Member Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.team')->with($notification);
    }

    /**
     * Delete the specified team member.
     */
    public function DeleteTeam($id)
    {
        $team = Team::findOrFail($id);

        // Delete image
        if ($team->image && File::exists(public_path($team->image))) {
            File::delete(public_path($team->image));
        }

        $team->delete();

        $notification = array(
            'message' => 'Team Member Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.team')->with($notification);
    }

    /**
     * Display the Book Area edit page.
     */
    public function BookArea()
    {
        $book = BookArea::first();

        if (!$book) {
            $book = BookArea::create([
                'short_title' => '',
                'main_title' => '',
                'short_desc' => '',
                'link_url' => '',
                'image' => null,
            ]);
        }

        return view('backend.bookarea.book_area', compact('book'));
    }

    /**
     * Update the Book Area information.
     */
    public function BookAreaUpdate(Request $request)
    {
        $book = BookArea::findOrFail($request->id);

        $data = $request->validate([
            'short_title' => 'nullable|string|max:255',
            'main_title' => 'nullable|string|max:255',
            'short_desc' => 'nullable|string',
            'link_url' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($book->image && File::exists(public_path($book->image))) {
                File::delete(public_path($book->image));
            }

            $image = $request->file('image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('upload/bookarea'), $name_gen);
            $data['image'] = 'upload/bookarea/' . $name_gen;
        } else {
            $data['image'] = $book->image;
        }

        $book->update($data);

        $notification = array(
            'message' => 'Book Area Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('book.area')->with($notification);
    }
}
