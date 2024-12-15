<?php

namespace App\Http\Controllers;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
// use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Validator;
// use App\Http\Requests\StoreblogsRequest;
// use App\Http\Requests\UpdateblogsRequest;


class Postcontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $post= Post::with('user')->get();
        return response()->json($post,200);
    }


public function store(Request $request)
{
    // Validate the incoming request data
    $request->validate([
        'title' => 'required|max:50',
        'body' => 'required|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate image if uploaded
    ]);

    // Initialize $image variable to null in case no image is uploaded
    $image = null;

    // Check if the request has an image file
    if ($request->hasFile('image')) {
        // Store the image in the 'public/images' folder
        $imagePath = $request->file('image')->store('public/images');
        // The $imagePath will return a path like "public/images/filename.jpg"
        // You need to save only the relative path to the image in the database
        // We remove the "public/" part so that the stored path is relative to the public folder
        $image = str_replace('public/', '', $imagePath);
    }

    // Try to create a new post
    try {
        $post = Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => Auth::id(),
            'image' => $image, // Store the relative path of the image in the database
        ]);

        // Return the post as a JSON response
        return response()->json($post, 201);
    } catch (\Exception $th) {
        // Return error if there is an issue
        return response()->json(['error' => $th->getMessage()], 403);
    }
}


    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return response()->json($post);
    }


    
    
    public function update(Request $request, Post $post)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image'=> 'image|mimes:jpeg,png,jpg,|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $post->title = $request->title;
        $post->content = $request->content;
        $post->save();

        return response()->json($post,200);

    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json(['message' => 'Blog deleted successfully'],204);
    }        
    }
