<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            Post::with('user')->withCount(['likes', 'comments'])->latest()->paginate(10)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        $post = $request->user()->posts()->create($validated);

        return response()->json($post->load('user'), 201);
    }

    public function show(Post $post)
    {
        return response()->json(
            $post->load(['user', 'comments.user'])->loadCount(['likes', 'comments'])
        );
    }

    public function update(Request $request, Post $post)
    {
        abort_unless($request->user()->id === $post->user_id, 403);

        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'body' => ['sometimes', 'required', 'string'],
        ]);

        $post->update($validated);

        return response()->json($post->fresh());
    }

    public function destroy(Request $request, Post $post)
    {
        abort_unless($request->user()->id === $post->user_id, 403);

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully.']);
    }

    
}
