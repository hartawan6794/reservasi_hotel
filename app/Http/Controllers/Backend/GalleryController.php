<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Models\Contact;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;

class GalleryController extends Controller
{
    /**
     * Display all gallery images (Backend)
     */
    public function AllGallery()
    {
        $gallery = Gallery::latest()->get();
        return view('backend.gallery.all_gallery', compact('gallery'));
    }

    /**
     * Show the add gallery form (Backend)
     */
    public function AddGallery()
    {
        return view('backend.gallery.add_gallery');
    }

    /**
     * Store gallery images (Backend)
     */
    public function StoreGallery(Request $request)
    {
        $request->validate([
            'photo_name' => 'required|array',
            'photo_name.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('photo_name')) {
            $images = $request->file('photo_name');
            
            foreach ($images as $image) {
                if ($image->isValid()) {
                    $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('upload/gallery'), $name_gen);
                    
                    Gallery::create([
                        'photo_name' => 'upload/gallery/' . $name_gen,
                    ]);
                }
            }
        }

        $notification = array(
            'message' => 'Gallery Images Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.gallery')->with($notification);
    }

    /**
     * Show the edit gallery form (Backend)
     */
    public function EditGallery($id)
    {
        $gallery = Gallery::findOrFail($id);
        return view('backend.gallery.edit_gallery', compact('gallery'));
    }

    /**
     * Update gallery image (Backend)
     */
    public function UpdateGallery(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:galleries,id',
            'photo_name' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $gallery = Gallery::findOrFail($request->id);

        if ($request->hasFile('photo_name')) {
            // Delete old image
            if ($gallery->photo_name && File::exists(public_path($gallery->photo_name))) {
                File::delete(public_path($gallery->photo_name));
            }

            $image = $request->file('photo_name');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('upload/gallery'), $name_gen);
            
            $gallery->photo_name = 'upload/gallery/' . $name_gen;
            $gallery->save();
        }

        $notification = array(
            'message' => 'Gallery Image Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.gallery')->with($notification);
    }

    /**
     * Delete gallery image (Backend)
     */
    public function DeleteGallery($id)
    {
        $gallery = Gallery::findOrFail($id);

        // Delete image file
        if ($gallery->photo_name && File::exists(public_path($gallery->photo_name))) {
            File::delete(public_path($gallery->photo_name));
        }

        $gallery->delete();

        $notification = array(
            'message' => 'Gallery Image Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    /**
     * Delete multiple gallery images (Backend)
     */
    public function DeleteGalleryMultiple(Request $request)
    {
        $request->validate([
            'selectedItem' => 'required|array',
            'selectedItem.*' => 'exists:galleries,id',
        ]);

        $selectedItems = $request->selectedItem;

        foreach ($selectedItems as $itemId) {
            $gallery = Gallery::findOrFail($itemId);

            // Delete image file
            if ($gallery->photo_name && File::exists(public_path($gallery->photo_name))) {
                File::delete(public_path($gallery->photo_name));
            }

            $gallery->delete();
        }

        $notification = array(
            'message' => 'Selected Gallery Images Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    /**
     * Display all contact messages (Backend)
     */
    public function AdminContactMessage()
    {
        $contact = Contact::latest()->get();
        return view('backend.contact.contact_message', compact('contact'));
    }

    /**
     * Display gallery page (Frontend)
     */
    public function ShowGallery()
    {
        $gallery = Gallery::latest()->get();
        return view('frontend.gallery.show_gallery', compact('gallery'));
    }

    /**
     * Show contact us page (Frontend)
     */
    public function ContactUs()
    {
        return view('frontend.contact.contact_us');
    }

    /**
     * Store contact message (Frontend)
     */
    public function StoreContactUs(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        $notification = array(
            'message' => 'Your Message Sent Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}