<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\RoomType;
class RoomTypeController extends Controller
{

    //function RoomTypeList
    //backend.allroom.roomtype.view_roomtype
    //data = alldata
    public function RoomTypeList()
    {
        $allData = RoomType::all();
        return view('backend.allroom.roomtype.view_roomtype', compact('allData'));
    }
   
    //function AddRoomType
    //backend.allroom.roomtype.add_roomtype
    public function AddRoomType()
    {
        return view('backend.allroom.roomtype.add_roomtype');
    }
    //function StoreRoomType
    public function Store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $roomtype = new RoomType();
        $roomtype->name = $request->name;
        $roomtype->save();
    }
    ///room/type/store
    public function RoomTypeStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $roomtype = new RoomType();
        $roomtype->name = $request->name;
        $roomtype->save();
        return redirect()->route('room.type.list')->with('success', 'Room Type added successfully');
    }
}