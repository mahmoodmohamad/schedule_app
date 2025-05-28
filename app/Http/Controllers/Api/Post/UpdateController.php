<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Post $post)
    {
        //
        if ($post->status != 'scheduled') {
            return response()->json([
                'message' => 'This is not scheduled post',
            ]);
        }
        $data = $request->validate(

            [
                'title' => 'required|max:255|string',
                'content' => 'required|max:1000|string',
                'image_url' => 'nullable',
                'schedule_time' => 'required|date',
                'status' => 'required',
                'platform_ids' => 'required|array',
                'platform_ids.*' => 'exists:platforms,id'
            ]
        );
        $post->update($data);
        return response()->json([
            'message'=>'The scheduled post updated successfully',
            'post'=>$post,
        ]);
    }
}
