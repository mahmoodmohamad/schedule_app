<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(Request $request, Post $post)
    {
        abort_if($post->user_id !== $request->user()->id, 403);

        if ($post->status !== 'scheduled') {
            return response()->json(['message' => 'Only scheduled posts can be updated.'], 422);
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:1000',
            'image_url' => 'nullable|string',
            'schedule_time' => 'required|date',
            'status' => 'required|in:draft,scheduled,published,failed',
            'platform_ids' => 'required|array',
            'platform_ids.*' => 'exists:platforms,id',
        ]);

        $platformIds = $data['platform_ids'];
        unset($data['platform_ids']);

        $post->update($data);
        $post->platforms()->sync(
            collect($platformIds)->mapWithKeys(fn ($id) => [$id => ['platform_status' => 'active']])->all()
        );

        return response()->json([
            'message' => 'The scheduled post updated successfully',
            'post' => $post->load('platforms'),
        ]);
    }
}