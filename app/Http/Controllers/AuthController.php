<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $user = $request->validate([
            'firstname'=> 'required',
            'lastname' => 'required',
            'pin' => 'required|digits:8|numeric',
            'role' => 'required|unique:users,role',
        ]);

        $user = new User;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->role = $request->role;
        $user->pin = Hash::make($request->pin);
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
        $request->validate([
            'pin' => 'required|digits:8|numeric',
        ]);
        // if(!auth()->attempt($request->only('pin'))){
        //     return response()->json([
        //             'message'=> 'invalid credentials'
        //         ]);
        // }

         $user = Auth::login($request->pin);
            // $user = Hash::check($request->pin);
        return $user;

        // $pin =  Hash::make($request->pin);
        //     $user =  User::where('pin',$pin)->first();
        //     return $pin;

        // $accessToken = auth()->user()->createToken('user_token')->accessToken;
        // return response()->json([
        //     'date'=> [
        //         'message'=> 'Logged in Successfully',
        //         'access_token' => $accessToken,
        //         'attributes' => [
        //             auth()->user()
        //         ]
        //     ]
        // ]);
    }
}
