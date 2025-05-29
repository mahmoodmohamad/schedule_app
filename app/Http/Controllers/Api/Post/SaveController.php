<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaveController extends Controller
{
    /**
     * Handle the incoming request.
     */
     public function __invoke(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|max:255|string',
            'content' => 'required|max:1000|string',
            'image_url' => 'nullable',
            'schedule_time' => 'required|date',
            'status' => 'required',
            'platform_ids' => 'required|array',
            'platform_ids.*' => 'exists:platforms,id'
        ]);

        $user = Auth::user();

        // 🔐 Check how many posts the user has scheduled for that day
        $scheduledDate = Carbon::parse($data['schedule_time'])->toDateString();
        $scheduledCount = Post::where('user_id', $user->id)
            ->whereDate('schedule_time', $scheduledDate)
            ->count();

        if ($scheduledCount >= 10) {
            return response()->json([
                'message' => 'You can only schedule up to 10 posts per day.'
            ], 429); // 429 Too Many Requests
        }

        //  Proceed to save
        $data['user_id'] = $user->id;
        $post = Post::create($data);
        $post->platforms()->attach($request->platform_ids);

        return response()->json([
            'post' => $post->load('platforms'),
        ]);
    }
}
