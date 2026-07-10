<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function Login(Request $request)
    {
        $validateData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);
        $user = User::where('email', $validateData['email'])->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }
        $token = $user->createToken('api_token')->plainTextToken;
        return response()->json([
            'status' => 'success',
            'data' => $user,
            'token' => $token
        ], 200);
    }
    public function logout(Request $request){
         $request->user()->currentAccessToken()->delete();
         return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully'
         ], 200);
    }
}
