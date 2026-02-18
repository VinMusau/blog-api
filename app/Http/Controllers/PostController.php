<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Models\User;
class PostController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show', 'userPosts']),  // Apply auth:sanctum middleware to all methods except index and show
            
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       // $posts = Post::all();
        
        $posts = Post::with(['category','user'])->latest()->get(); 
        // return view('posts.index', compact('posts'));
        return response()->json($posts);
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields= $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);
        $post= $request->user()->posts()->create($fields);  // create relation to user store method looks for authenticated user
       // return ['post' => $post, 'user' => $post->user];
       return $post->load('category');
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
    public function userPosts (User $user) 
    {
        return $user->posts()->with(['user','category'])->latest()->get();
        //$posts = Post::where('user_id', $id)->with('user', 'category')->latest()->get();
      
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
