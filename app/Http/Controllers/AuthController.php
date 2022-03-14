<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'pin' => 'required|digits:6|numeric',
            'role' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'data' => $validator->errors(),
            ], 422);
        }

        $request['pin'] = md5($request->pin);

        $userWithPinExists = User::where('pin', $request->pin)->exists();
        if ($userWithPinExists) {
            return response()->json([
                'success' => false,
                'message' => 'User with this PIN already exists.',
            ], 422);
        }

        $user = User::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pin' => 'required|digits:6|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'data' => $validator->errors(),
            ], 422);
        }

        $request['pin'] = md5($request->pin);

        $user = User::where('pin', $request->pin)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        Auth::login($user);

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }
}
