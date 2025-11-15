<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;
use Illuminate\Support\Facades\File;

class TestimonialController extends Controller
{
    /**
     * Display all testimonials (Backend)
     */
    public function AllTestimonial()
    {
        $testimonial = Testimonial::latest()->get();
        return view('backend.tesimonial.all_tesimonial', compact('testimonial'));
    }

    /**
     * Show the add testimonial form (Backend)
     */
    public function AddTestimonial()
    {
        return view('backend.tesimonial.add_tesimonial');
    }

    /**
     * Store a new testimonial (Backend)
     */
    public function StoreTestimonial(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'message' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('upload/testimonial'), $name_gen);
            
            Testimonial::create([
                'name' => $request->name,
                'city' => $request->city,
                'message' => $request->message,
                'image' => 'upload/testimonial/' . $name_gen,
            ]);
        }

        $notification = array(
            'message' => 'Testimonial Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.testimonial')->with($notification);
    }

    /**
     * Show the edit testimonial form (Backend)
     */
    public function EditTestimonial($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        return view('backend.tesimonial.edit_testimonial', compact('testimonial'));
    }

    /**
     * Update an existing testimonial (Backend)
     */
    public function UpdateTestimonial(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:testimonials,id',
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'message' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $testimonial = Testimonial::findOrFail($request->id);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($testimonial->image && File::exists(public_path($testimonial->image))) {
                File::delete(public_path($testimonial->image));
            }

            $image = $request->file('image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('upload/testimonial'), $name_gen);
            
            $testimonial->image = 'upload/testimonial/' . $name_gen;
        }

        $testimonial->name = $request->name;
        $testimonial->city = $request->city;
        $testimonial->message = $request->message;
        $testimonial->save();

        $notification = array(
            'message' => 'Testimonial Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.testimonial')->with($notification);
    }

    /**
     * Delete a testimonial (Backend)
     */
    public function DeleteTestimonial($id)
    {
        $testimonial = Testimonial::findOrFail($id);

        // Delete image file
        if ($testimonial->image && File::exists(public_path($testimonial->image))) {
            File::delete(public_path($testimonial->image));
        }

        $testimonial->delete();

        $notification = array(
            'message' => 'Testimonial Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}