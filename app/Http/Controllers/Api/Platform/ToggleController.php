<?php

namespace App\Http\Controllers\Api\Platform;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use App\Models\Post;
use App\Models\PostPlatform;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ToggleController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $userId,$platformId)
    {
        //
        $user = User::find($userId);

         $userPostIds = $user->posts()->pluck('id');
         if ($userPostIds->isEmpty()) {
            // If the user has no posts at all, they cannot have post_platform records
            return response()->json(['error' => 'User has no posts associated with any platform.'], 404);
        }
        $postPlatformRecords = PostPlatform::whereIn('post_id', $userPostIds)
            ->where('platform_id', $platformId)
            ->get();
    
        foreach ($postPlatformRecords as $record) {
            $record->platform_status = $record->platform_status === 'active' ? 'inactive' : 'active';
            $record->save();
        }

        return response()->json([
            'message' => 'Platform status toggled successfully for user posts.',
            'updated_records_count' => $postPlatformRecords->count(),
        ]);


 
       
    }
}
