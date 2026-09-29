<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __invoke(Request $request, User $user)
    {
        // Fix IDOR: only allow a user to read their own posts
        abort_if($request->user()->id !== $user->id, 403);

        $query = Post::where('user_id', $user->id)
            ->with(['user', 'platforms']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('schedule_time')) {
            $query->whereDate('schedule_time', $request->schedule_time);
        }

        $posts = $query->latest()->get();

        return response()->json([
            'posts' => $posts,
        ]);
    }
}