<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    //
    public function index(Request $request)
{
    return response()->json([
        'activities' => UserActivity::where('user_id', Auth::id())
            ->latest()
            ->limit(50)
            ->get()
    ]);
}

}
