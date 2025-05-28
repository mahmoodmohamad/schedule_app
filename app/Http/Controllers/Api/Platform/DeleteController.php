<?php

namespace App\Http\Controllers\Api\Platform;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    //
    public function __invoke(Platform $platform)
    {
        $platform->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
