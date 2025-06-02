<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;

use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register (Request $request) {
        $validated = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'nik' => 'required',
            'bod' => 'required',
            'ktp' => 'required',
        ]);

        if($validated->fails()) {
            return response()->json([
                'massage' => 'Data tidak valid',
                'data' => $validated->errors()
            ], 400);
        }

        try {
            $validated = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone,
                'id_role' => 3,
                'is_active' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $validated = Member::create([
                'id_user' => $validated->id,
                'address' => $request->address,
                'nik' => $request->nik,
                'bod' => $request->bod,
                'ktp' => $request->ktp,
                'is_verified' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'massage' => 'success',
                'data' => $validated
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'massage' => 'error',
                'data' => $e
            ], 500);
        }
    }
}
