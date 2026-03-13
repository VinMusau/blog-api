<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($postId)
    {
        return Comment::where('commentable_id', $postId)
            ->where('commentable_type', Post::class)
            ->with('user')
            ->latest()
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
            'post_id' => 'required|integer|exists:posts,id',
        ]);

        $comment = Comment::create([
            'body' => $request->body,
            'commentable_id' => $request->post_id,
            'commentable_type'=> Post::class,
            'is_approved'=> true,
            'user_id' => auth()->id(),
        ]);

        return response()->json($comment->load('user'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
