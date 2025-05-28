<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use function PHPUnit\Framework\returnArgument;

class LoginController  extends Controller
{
    //

    public function __invoke(Request $request)
    {
        $request->validate(
            [
                "email"=>$request->email,
                "password"=>$request->password,
            ]
            );
            $user=User::where('email', $request->email)->first();
            if(!$user || !Hash::check($request->password, $user->password))
            {
                return response()->json([
                    'message' => 'The provided credentials are incorrect.'
                ], 401);
            }
            $token=$user->createToken('APiToken')->plainTextToken;
            return response()->json([    'access_token'=>$token,
            'token_type'=>'Bearer',
            'user'=>$user,]
        );
        }

}