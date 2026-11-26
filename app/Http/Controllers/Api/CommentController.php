<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Post $post)
    {
        return response()->json(
            $post->comments()->with('user')->latest()->paginate(10)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'body' => ['required', 'string'],
        ]);

        $comment = $post->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);

        return response()->json($comment->load('user'), 201);
    }

    public function update(Request $request, Comment $comment)
    {
        abort_unless($request->user()->id === $comment->user_id, 403);

        $validated = $request->validate([
            'body' => ['required', 'string'],
        ]);

        $comment->update($validated);

        return response()->json($comment->fresh());
    }

    public function destroy(Request $request, Comment $comment)
    {
        abort_unless($request->user()->id === $comment->user_id, 403);

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully.']);
    }
}
