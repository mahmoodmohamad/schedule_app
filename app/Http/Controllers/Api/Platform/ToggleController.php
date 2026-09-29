<?php

namespace App\Http\Controllers\Api\Platform;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use App\Models\PostPlatform;
use Illuminate\Http\Request;

class ToggleController extends Controller
{
    public function __invoke(Request $request, Platform $platform)
    {
        // Fix IDOR: use authenticated user, not a URL param
        $user = $request->user();

        $userPostIds = $user->posts()->pluck('id');

        if ($userPostIds->isEmpty()) {
            return response()->json([
                'error' => 'User has no posts associated with any platform.',
            ], 404);
        }

        $records = PostPlatform::whereIn('post_id', $userPostIds)
            ->where('platform_id', $platform->id)
            ->get();

        foreach ($records as $record) {
            $record->platform_status = $record->platform_status === 'active'
                ? 'inactive'
                : 'active';
            $record->save();
        }

        return response()->json([
            'message' => 'Platform status toggled successfully for user posts.',
            'updated_records_count' => $records->count(),
        ]);
    }
}