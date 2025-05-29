<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class ListController extends Controller
{
    //
    public function index(Request $request)
    {
        $posts = Post::where('user_id', $request->user()->id)
                     ->latest()
                     ->get(['id', 'title', 'status', 'schedule_time']);

        return response()->json(['posts' => $posts]);
    }
}
