<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            "name" =>  "required|string|min:3|max:255",
            "email" => "required|string|email|max:255|unique:users",
            "password" => "required|string|min:6"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = User::create([
            "name" => $request->input("name"),
            "email" => $request->input("email"),
            "password" => Hash::make($request->input("password")),
        ]);

        return response()->json([
            "status" => "success",
            "message" => "User Created Successfully",
            "user" => $user,
        ]);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            "email" => "required|string|email|max:255",
            "password" => "required|string|min:6"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            "token" => $token
        ]);
    }
}
