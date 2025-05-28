<?php

namespace App\Http\Controllers\Api\Platform;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlatformRequest;
use App\Services\PlatformService;


class StoreController extends Controller
{
    //
     public function __invoke(StorePlatformRequest $request, PlatformService $platformService)
    {
        $platform = $platformService->store($request->validated());

        return response()->json(['platform' => $platform], 201);
    }
}
