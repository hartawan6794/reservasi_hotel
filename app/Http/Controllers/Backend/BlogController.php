<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display all blog categories (Backend)
     */
    public function BlogCategory()
    {
        $category = BlogCategory::latest()->get();
        return view('backend.category.blog_category', compact('category'));
    }

    /**
     * Store blog category (Backend)
     */
    public function StoreBlogCategory(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255|unique:blog_categories,category_name',
        ]);

        BlogCategory::create([
            'category_name' => $request->category_name,
            'category_slug' => Str::slug($request->category_name),
        ]);

        $notification = array(
            'message' => 'Blog Category Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    /**
     * Edit blog category - return JSON for AJAX (Backend)
     */
    public function EditBlogCategory($id)
    {
        $category = BlogCategory::findOrFail($id);
        return response()->json($category);
    }

    /**
     * Update blog category (Backend)
     */
    public function UpdateBlogCategory(Request $request)
    {
        $request->validate([
            'cat_id' => 'required|exists:blog_categories,id',
            'category_name' => 'required|string|max:255|unique:blog_categories,category_name,' . $request->cat_id,
        ]);

        $category = BlogCategory::findOrFail($request->cat_id);
        $category->update([
            'category_name' => $request->category_name,
            'category_slug' => Str::slug($request->category_name),
        ]);

        $notification = array(
            'message' => 'Blog Category Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    /**
     * Delete blog category (Backend)
     */
    public function DeleteBlogCategory($id)
    {
        $category = BlogCategory::findOrFail($id);
        $category->delete();

        $notification = array(
            'message' => 'Blog Category Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    /**
     * Display all blog posts (Backend)
     */
    public function AllBlogPost()
    {
        $post = BlogPost::with('blog')->latest()->get();
        return view('backend.post.all_post', compact('post'));
    }

    /**
     * Show the add blog post form (Backend)
     */
    public function AddBlogPost()
    {
        $blogcat = BlogCategory::latest()->get();
        return view('backend.post.add_post', compact('blogcat'));
    }

    /**
     * Store blog post (Backend)
     */
    public function StoreBlogPost(Request $request)
    {
        $request->validate([
            'blogcat_id' => 'required|exists:blog_categories,id',
            'post_titile' => 'required|string|max:255',
            'short_descp' => 'required|string',
            // 'long_descp' => 'required|string',
            'post_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['post_slug'] = Str::slug($request->post_titile);

        // Handle image upload
        if ($request->hasFile('post_image')) {
            $image = $request->file('post_image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('upload/post'), $name_gen);
            $data['post_image'] = 'upload/post/' . $name_gen;
        }

        BlogPost::create($data);

        $notification = array(
            'message' => 'Blog Post Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.blog.post')->with($notification);
    }

    /**
     * Show the edit blog post form (Backend)
     */
    public function EditBlogPost($id)
    {
        $post = BlogPost::findOrFail($id);
        $blogcat = BlogCategory::latest()->get();
        return view('backend.post.edit_post', compact('post', 'blogcat'));
    }

    /**
     * Update blog post (Backend)
     */
    public function UpdateBlogPost(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:blog_posts,id',
            'blogcat_id' => 'required|exists:blog_categories,id',
            'post_titile' => 'required|string|max:255',
            'short_descp' => 'required|string',
            'long_descp' => 'required|string',
            'post_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $post = BlogPost::findOrFail($request->id);

        $data = $request->all();
        $data['post_slug'] = Str::slug($request->post_titile);

        // Handle image upload
        if ($request->hasFile('post_image')) {
            // Delete old image
            if ($post->post_image && File::exists(public_path($post->post_image))) {
                File::delete(public_path($post->post_image));
            }

            $image = $request->file('post_image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('upload/post'), $name_gen);
            $data['post_image'] = 'upload/post/' . $name_gen;
        } else {
            unset($data['post_image']);
        }

        $post->update($data);

        $notification = array(
            'message' => 'Blog Post Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.blog.post')->with($notification);
    }

    /**
     * Delete blog post (Backend)
     */
    public function DeleteBlogPost($id)
    {
        $post = BlogPost::findOrFail($id);

        // Delete image file
        if ($post->post_image && File::exists(public_path($post->post_image))) {
            File::delete(public_path($post->post_image));
        }

        $post->delete();

        $notification = array(
            'message' => 'Blog Post Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    /**
     * Display blog list page (Frontend)
     */
    public function BlogList()
    {
        $blog = BlogPost::with('blog', 'user')->latest()->paginate(3);
        $bcategory = BlogCategory::latest()->get();
        $lpost = BlogPost::with('blog', 'user')->latest()->limit(5)->get();
        return view('frontend.blog.blog_all', compact('blog', 'bcategory', 'lpost'));
    }

    /**
     * Display blog details page (Frontend)
     */
    public function BlogDetails($slug)
    {
        $blog = BlogPost::with('blog', 'user')->where('post_slug', $slug)->firstOrFail();
        $bcategory = BlogCategory::latest()->get();
        $lpost = BlogPost::with('blog', 'user')->latest()->limit(5)->get();
        return view('frontend.blog.blog_details', compact('blog', 'bcategory', 'lpost'));
    }

    /**
     * Display blog list by category (Frontend)
     */
    public function BlogCatList($id)
    {
        $blog = BlogPost::with('blog', 'user')->where('blogcat_id', $id)->latest()->get();
        $namecat = BlogCategory::findOrFail($id);
        $bcategory = BlogCategory::latest()->get();
        $lpost = BlogPost::with('blog', 'user')->latest()->limit(5)->get();
        return view('frontend.blog.blog_cat_list', compact('blog', 'namecat', 'bcategory', 'lpost'));
    }
}