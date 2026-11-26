<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            User::query()
                ->select('id', 'name', 'email', 'created_at')
                ->latest()
                ->paginate(10)
        );
    }

    public function show(User $user)
    {
        return response()->json(
            $user->only(['id', 'name', 'created_at'])
        );
    }

    public function update(Request $request, User $user)
    {
        abort_unless($request->user()->id === $user->id, 403);

        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($validated);

        return response()->json($user->only(['id', 'name', 'email']));
    }

    public function destroy(Request $request, User $user)
    {
        abort_unless($request->user()->id === $user->id, 403);

        $user->delete();

        return response()->json(['message' => 'Account deleted successfully.']);
    }
}
