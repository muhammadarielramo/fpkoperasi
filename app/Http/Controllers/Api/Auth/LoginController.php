<?php

namespace App\Http\Controllers\Api\Auth;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Mail\ResetPassword;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'messages' => 'Data tidak valid',
                'data' => $validator->messages()
            ], 400);
        }

        $credentials = request(['username', 'password']);
        $credentials['is_active'] = 1;

        if (auth()->guard('api')->attempt($credentials)) {
            $user = auth()->guard('api')->user();
            $token = JWTAuth::fromUser($user);

            return response()->json([
                'message' => 'Login berhasil',
                'token' => $token,
                'user' => $user,
            ], 200);
     }

        return response()->json([
            'message' => 'Email, password, atau status akun tidak valid',
        ], 401);

    }

    public function logout()   {
        JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json([
                'success' => true,
                'message' => 'Logout successful'
            ], 200);
    }

}

