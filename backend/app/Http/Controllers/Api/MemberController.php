<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class MemberController extends Controller
{
    public function updateProfile(Request $request) {
        // get user
        $user = auth()->user();

        // validasi
        $validator = Validator::make($request->all(), [
            'username' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:6',
            'address' => 'sometimes|string',
            'phone_number' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'messages' => $validator->messages(),
                422]);
        }

        if($request->has('username')){
            $user->username = $request->username;
        }
        if($request->has('email')){
            $user->email = $request->email;
        }
        if($request->has('password')){
            $user->password = Hash::make($request->password);
        }
        if($request->has('address')){
            if ($user->member) {
                $user->member->address = $request->address;
                $user->member->save();
            }
        }
        if($request->has('phone')){
            $user->phone = $request->phone;
        }

        $user->updated_at = now();

        if($user->save()) {
            return response()->json([
                'success' => true,
                'messages' => 'Profile berhasil diupdate',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'messages' => 'Profile gagal diupdate',
            ], 500);
        }
    }

    public function register(Request $request) {
        // validasi
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required',
            'alamat' => 'required',
            'bod' => 'required',
            'gender' => 'required',
            'NIK' => 'required',
            // 'ktp' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        // simpan ke db
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'id_role' => 3,
            'is_active' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $member = Member::create([
            'id_user' => $user->id,
            'nik' => $request->NIK,
            'bod' => $request->bod,
            'address' => $request->alamat,
            'gender' => $request->gender,
            'is_verified' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // buat token
        $token = JWTAuth::fromUser($user);

        // respon
        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $token,
        ], 201);
    }
}
