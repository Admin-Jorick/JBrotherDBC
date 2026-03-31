<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // Save new post
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        

        $post = new Post();
        $post->title = $request->title;
        $post->description = $request->description;

        $paths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Save image directly in public/storage/posts
                $filename = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('storage/posts'), $filename);
                $paths[] = 'posts/' . $filename; // store relative path
            }
        }
        $post->image = json_encode($paths);

        $post->save();
        return back()->with('success', 'Post created successfully!');
    }

    // Edit form
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $post->images = $post->image ? json_decode($post->image, true) : [];
        return view('admineditpost', compact('post'));
    }

    // Update post
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $post = Post::findOrFail($id);
        $post->title = $request->title;
        $post->description = $request->description;

        // Kunin existing images
        $currentImages = $post->image ? json_decode($post->image, true) : [];

        // Kung may naka-check na delete_images
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $index) {
                if (isset($currentImages[$index])) {
                    if (Storage::disk('public')->exists($currentImages[$index])) {
                        Storage::disk('public')->delete($currentImages[$index]);
                    }
                    unset($currentImages[$index]);
                }
            }
            // Re-index para tuloy-tuloy ulit ang array keys
            $currentImages = array_values($currentImages);
        }

        // Upload new images kung meron
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $currentImages[] = $file->store('posts', 'public');
            }
        }

        // I-save ulit yung updated data
        $post->image = json_encode($currentImages);
        $post->save();

        // Redirect pabalik sa admin page
        return redirect()->route('admin')->with('success', 'Post updated successfully!');
    }

    // Delete post
   public function destroy($id)
    {
        $post = Post::findOrFail($id);

        // Decode stored images
        $images = $post->image ? json_decode($post->image, true) : [];

        if (is_array($images)) {
            foreach ($images as $img) {
                $filePath = public_path('storage/' . $img);
                if (file_exists($filePath)) {
                    unlink($filePath); // delete the image
                }
            }
        }

        $post->delete();

        return back()->with('success', 'Post and its images deleted successfully!');
    }

}
