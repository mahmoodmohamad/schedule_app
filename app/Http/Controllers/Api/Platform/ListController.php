<?php

namespace App\Http\Controllers\Api\Platform;

use App\Http\Controllers\Controller;
use App\Services\PlatformService;
use Illuminate\Http\Request;

class ListController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, PlatformService $platformService)
    {
        $platforms = $platformService->index();
        return response()->json(['platforms' => $platforms]);
    }
}
