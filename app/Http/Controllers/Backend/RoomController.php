<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\MultiImage;
use App\Models\Room;
use App\Models\RoomNumber;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class RoomController extends Controller
{
    /**
     * Show all rooms
     */
    public function AllRoom()
    {
        $allData = Room::with('type')->latest()->get();
        return view('backend.allroom.rooms.all_room', compact('allData'));
    }

    /**
     * Show add room form
     */
    public function AddRoom()
    {
        $roomtypes = RoomType::all();
        return view('backend.allroom.rooms.add_room', compact('roomtypes'));
    }

    /**
     * Store new room
     */
    public function StoreRoom(Request $request)
    {
        $validated = $request->validate([
            'roomtype_id' => 'required|integer|exists:room_types,id',
            'total_adult' => 'nullable|string|max:255',
            'total_child' => 'nullable|string|max:255',
            'room_capacity' => 'nullable|string|max:255',
            'price' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'view' => 'nullable|string|max:255',
            'bed_style' => 'nullable|string|max:255',
            'discount' => 'nullable|integer|min:0|max:100',
            'short_desc' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'multi_img' => 'nullable|array',
            'multi_img.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'facility_name' => 'nullable|array',
        ]);

        // Handle main image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('upload/roomimg'), $name_gen);
            $validated['image'] = $name_gen;
        }

        // Set default values for required fields (discount and status have defaults in DB)
        $validated['discount'] = $validated['discount'] ?? 0;
        $validated['status'] = 1; // Default status is 1 (active)

        // Remove fields that don't belong to rooms table
        unset($validated['multi_img']);
        unset($validated['facility_name']);

        // Create room first to get the room ID
        $room = Room::create($validated);
        
        // Get the room ID after creation
        $roomId = $room->id;

        // Handle multi images - only if files are uploaded
        if ($request->hasFile('multi_img')) {
            $multiImages = $request->file('multi_img');
            foreach ($multiImages as $image) {
                // Validate each image
                if ($image->isValid()) {
                    $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('upload/roomimg/multi_img'), $name_gen);

                    MultiImage::create([
                        'rooms_id' => $roomId,
                        'multi_img' => $name_gen,
                    ]);
                }
            }
        }

        // Handle facilities - only insert non-empty values
        if ($request->has('facility_name') && is_array($request->facility_name)) {
            foreach ($request->facility_name as $facility_name) {
                // Only insert if facility_name is not empty and not null
                if (!empty($facility_name) && trim($facility_name) !== '') {
                    Facility::create([
                        'rooms_id' => $roomId,
                        'facility_name' => trim($facility_name),
                    ]);
                }
            }
        }

        return redirect()->route('all.room')->with('success', 'Room added successfully.');
    }

    /**
     * Show edit room form
     */
    public function EditRoom($id)
    {
        $editData = Room::with('type')->findOrFail($id);
        $roomtypes = RoomType::all();
        $multiimgs = MultiImage::where('rooms_id', $id)->get();
        $basic_facility = Facility::where('rooms_id', $id)->get();
        $allroomNo = RoomNumber::where('rooms_id', $id)->get();

        return view('backend.allroom.rooms.edit_rooms', compact('editData', 'roomtypes', 'multiimgs', 'basic_facility', 'allroomNo'));
    }

    /**
     * Update room information
     */
    public function UpdateRoom(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $validated = $request->validate([
            'roomtype_id' => 'required|integer|exists:room_types,id',
            'total_adult' => 'nullable|string|max:255',
            'total_child' => 'nullable|string|max:255',
            'room_capacity' => 'nullable|string|max:255',
            'price' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'view' => 'nullable|string|max:255',
            'bed_style' => 'nullable|string|max:255',
            'discount' => 'nullable|integer|min:0|max:100',
            'short_desc' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'nullable|integer|in:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'multi_img' => 'nullable|array',
            'multi_img.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'facility_name' => 'nullable|array',
        ]);

        // Ensure discount has a default value if not provided
        if (!isset($validated['discount'])) {
            $validated['discount'] = $room->discount ?? 0;
        }

        // Update main image if provided
        if ($request->hasFile('image')) {
            // Delete old image
            if ($room->image && File::exists(public_path('upload/roomimg/' . $room->image))) {
                File::delete(public_path('upload/roomimg/' . $room->image));
            }

            $image = $request->file('image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('upload/roomimg'), $name_gen);
            $validated['image'] = $name_gen;
        }

        // Remove fields that don't belong to rooms table
        unset($validated['multi_img']);
        unset($validated['facility_name']);

        // Update room data
        $room->update($validated);

        // Handle multi images - only if files are uploaded
        if ($request->hasFile('multi_img')) {
            $multiImages = $request->file('multi_img');
            foreach ($multiImages as $image) {
                // Validate each image
                if ($image->isValid()) {
                    $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('upload/roomimg/multi_img'), $name_gen);

                    MultiImage::create([
                        'rooms_id' => $id,
                        'multi_img' => $name_gen,
                    ]);
                }
            }
        }

        // Handle facilities - only insert non-empty values
        if ($request->has('facility_name') && is_array($request->facility_name)) {
            // Delete existing facilities
            Facility::where('rooms_id', $id)->delete();

            // Create new facilities - only insert non-empty values
            foreach ($request->facility_name as $facility_name) {
                // Only insert if facility_name is not empty and not null
                if (!empty($facility_name) && trim($facility_name) !== '') {
                    Facility::create([
                        'rooms_id' => $id,
                        'facility_name' => trim($facility_name),
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Room updated successfully.');
    }

    /**
     * Delete multi image
     */
    public function MultiImageDelete($id)
    {
        $multiImg = MultiImage::findOrFail($id);
        
        // Delete image file
        if ($multiImg->multi_img && File::exists(public_path('upload/roomimg/multi_img/' . $multiImg->multi_img))) {
            File::delete(public_path('upload/roomimg/multi_img/' . $multiImg->multi_img));
        }

        $multiImg->delete();

        return redirect()->back()->with('success', 'Multi image deleted successfully.');
    }

    /**
     * Store room number
     */
    public function StoreRoomNumber(Request $request, $id)
    {
        $validated = $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'room_no' => [
                'required',
                'string',
                'max:255',
                'unique:room_numbers,room_no,NULL,id,rooms_id,' . $id
            ],
            'status' => 'required|in:Active,Inactive',
        ]);

        RoomNumber::create([
            'rooms_id' => $id,
            'room_type_id' => $validated['room_type_id'],
            'room_no' => $validated['room_no'],
            'status' => $validated['status'],
        ]);

        return redirect()->back()->with('success', 'Room number added successfully.');
    }

    /**
     * Show edit room number form
     */
    public function EditRoomNumber($id)
    {
        $editroomno = RoomNumber::findOrFail($id);
        return view('backend.allroom.rooms.edit_room_no', compact('editroomno'));
    }

    /**
     * Update room number
     */
    public function UpdateRoomNumber(Request $request, $id)
    {
        $roomNumber = RoomNumber::findOrFail($id);

        $validated = $request->validate([
            'room_no' => [
                'required',
                'string',
                'max:255',
                'unique:room_numbers,room_no,' . $id . ',id,rooms_id,' . $roomNumber->rooms_id
            ],
            'status' => 'required|in:Active,Inactive',
        ]);

        $roomNumber->update($validated);

        return redirect()->route('edit.room', $roomNumber->rooms_id)
            ->with('success', 'Room number updated successfully.');
    }

    /**
     * Delete room number
     */
    public function DeleteRoomNumber($id)
    {
        $roomNumber = RoomNumber::findOrFail($id);
        $room_id = $roomNumber->rooms_id;
        $roomNumber->delete();

        return redirect()->route('edit.room', $room_id)
            ->with('success', 'Room number deleted successfully.');
    }

    /**
     * Delete room
     */
    public function DeleteRoom($id)
    {
        $room = Room::findOrFail($id);

        // Delete main image
        if ($room->image && File::exists(public_path('upload/roomimg/' . $room->image))) {
            File::delete(public_path('upload/roomimg/' . $room->image));
        }

        // Delete multi images
        $multiImages = MultiImage::where('rooms_id', $id)->get();
        foreach ($multiImages as $multiImg) {
            if ($multiImg->multi_img && File::exists(public_path('upload/roomimg/multi_img/' . $multiImg->multi_img))) {
                File::delete(public_path('upload/roomimg/multi_img/' . $multiImg->multi_img));
            }
        }

        // Delete related data
        MultiImage::where('rooms_id', $id)->delete();
        Facility::where('rooms_id', $id)->delete();
        RoomNumber::where('rooms_id', $id)->delete();

        // Delete room
        $room->delete();

        return redirect()->back()->with('success', 'Room deleted successfully.');
    }
}
