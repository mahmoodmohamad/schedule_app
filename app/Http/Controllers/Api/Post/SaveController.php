<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SaveController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:1000',
            'image_url' => 'nullable|string',
            'schedule_time' => 'nullable|date',
            'status' => 'required|in:draft,scheduled,published',
            'platform_ids' => 'required|array',
            'platform_ids.*' => 'exists:platforms,id',
        ]);

        $user = $request->user();

        // Daily cap (only when a schedule_time is provided)
        if (!empty($data['schedule_time'])) {
            $scheduledDate = Carbon::parse($data['schedule_time'])->toDateString();

            $scheduledCount = Post::where('user_id', $user->id)
                ->whereDate('schedule_time', $scheduledDate)
                ->count();

            if ($scheduledCount >= 10) {
                return response()->json([
                    'message' => 'You can only schedule up to 10 posts per day.',
                ], 429);
            }
        }

        $data['user_id'] = $user->id;

        $post = Post::create($data);
        $post->platforms()->attach(
    collect($data['platform_ids'])
        ->mapWithKeys(fn ($id) => [$id => ['platform_status' => 'active']])
        ->all()
);

        return response()->json([
            'post' => $post->load('platforms'),
        ], 201);
    }
}