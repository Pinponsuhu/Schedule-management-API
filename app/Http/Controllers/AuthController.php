<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $user = $request->validate([
            'username'=> 'required|unique:users,username',
            'fullname' => 'required',
            'password' => 'required|digits:8|numeric',
            'email' => 'required|email',
        ]);

        $user = new User;
        $user->username = $request->username;
        $user->fullname = $request->fullname;
        $user->email = $request->email;
        $user->password = Hash::make($request->pin);
        $user->permission = false;
        $user->save();

        $token = $user->createToken('Auth_Token')->accessToken;
        return response()->json([
            'data'=> [
                'messsage' => 'Account created Successfully',
                'access_token' => $token,
            ]
                ]);
    }

    public function login(Request $request){
        $user = $request->validate([
            'username' => 'required',
            'password' => 'required|digits:8|numeric',
        ]);
        if(!auth()->attempt($user)){
            return response()->json([
                    'message'=> 'invalid credentials'
                ]);
        }
        $accessToken = auth()->user()->createToken('user_token')->accessToken;
        return response()->json([
            'date'=> [
                'message'=> 'Logged in Successfully',
                'access_token' => $accessToken,
                'attributes' => [
                    auth()->user()
                ]
            ]
        ]);
    }
}
