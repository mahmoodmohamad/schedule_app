<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, User $user)
    {
        //
        $query = Post::with('user');
        if($request->has('status'))
        {
            $query->where('status',$request->status);
        }
           if($request->has('schedule_time'))
        {
            $query->where('schedule_time',$request->schedule_time);
        }
        $posts=$query->get();
        return response()->json([
            'posts'=>$posts
        ]);
        }
}
