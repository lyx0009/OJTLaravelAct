<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Post $post)
    {
        $request->user()->likes()->firstOrCreate([
            'post_id' => $post->id,
        ]);

        return response()->json([
            'message' => 'Post liked.',
            'likes_count' => $post->likes()->count(),
        ], 201);
    }

    public function destroy(Request $request, Post $post)
    {
        $request->user()->likes()
            ->where('post_id', $post->id)
            ->delete();

        return response()->json([
            'message' => 'Post unliked.',
            'likes_count' => $post->likes()->count(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }
}
