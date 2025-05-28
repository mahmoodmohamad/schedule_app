<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $postId)
    {
        $post = Post::find($postId);
        //
         if (!$post) {
            return response()->json([
                'message' => 'Post not found',
                'success' => false
            ], 404); // HTTP 404 Not Found status
        }

        $post->platforms()->detach();
        $post->delete();
        return response()->json([
            "message"=>'Post deleted successfully',
        ]);
    }
}
