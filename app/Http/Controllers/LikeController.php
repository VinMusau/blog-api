<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Request $request, $postId){
        $user = $request->user();

        $like = $user->likes()->where('post_id, $postId')->first();

        if ($like) {
            $like->delete();
            return response()->json(['liked'=> false]);
        } else {
            $user->likes()->create(['post_id' => $postId]);
            return response()->json(['liked' => true]);
        }
    }
}
