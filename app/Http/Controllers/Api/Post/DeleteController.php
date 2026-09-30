<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    public function __invoke(Request $request, Post $post)
    {
        abort_if($post->user_id !== $request->user()->id, 403);

        $post->platforms()->detach();
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }
}