<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Request $request, Post $post){
        $user = $request->user();

        $result= $user->likedPosts()->toggle($post->id);
       
        return response()->json([
                'liked' => in_array($post->id, $result['attached']),
                'likes_count' => $post->likes()->count(),
            ]);
                   // $posts = Post::withCount('likes')->get();

    }

    public function index(Request $request)
    {
        return $request->user()->likedPosts()->pluck('posts.id'); 
    }
}
