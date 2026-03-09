<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Request $request, Post $post){
        $user = $request->user();

        $result= $user->likes()->toggle($post->id);
       
        return response()->json([
                'liked' => in_array($post->id, $result['attached']),
                'likes_count' => $post->likes()->count(),
            ]);
                   // $posts = Post::withCount('likes')->get();

    }
}
