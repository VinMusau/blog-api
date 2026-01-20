<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PostController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show']),  // Apply auth:sanctum middleware to all methods except index and show
            
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       // $posts = Post::all();
        
        return Post::with('user')->latest()->get(); // Eager load the user relationship
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields= $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        $post= $request->user()->posts()->create($fields);  // create relation to user store method looks for authenticated user
        return ['post' => $post, 'user' => $post->user];
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return ['post'=>$post, 'user' => $post->user];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        Gate::authorize('update', $post);


        $fields = $request->validated([

        ]);
        $post->update($fields);

        return ['post' => $post, 'user' => $post->user];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        Gate::authorize('delete', $post);

        $post->delete();
        return response()->json(['message' => 'Post deleted successfully']);
    }
}
