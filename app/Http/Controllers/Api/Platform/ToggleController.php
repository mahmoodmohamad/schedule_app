<?php

namespace App\Http\Controllers\Api\Platform;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use Illuminate\Http\Request;

class ToggleController extends Controller
{
    public function __invoke(Request $request, Platform $platform)
    {
        $user = $request->user();

        $row = $user->platforms()->where('platforms.id', $platform->id)->first();

        // مفيش row = مفعّلة by default، فالـ toggle يقفلها
        $isActive = $row ? ! $row->pivot->is_active : false;

        $user->platforms()->syncWithoutDetaching([
            $platform->id => ['is_active' => $isActive],
        ]);

        return response()->json([
            'message' => 'Platform status updated.',
            'platform_id' => $platform->id,
            'is_active' => $isActive,
        ]);
    }
}