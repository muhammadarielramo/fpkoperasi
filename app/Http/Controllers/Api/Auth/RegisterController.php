<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;

use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    public function register (Request $request) {
        $validated = Validator::make($request->all(), [
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'phone'   => ['required', 'string', 'max:15'],
            'address' => ['required', 'string'],
            'nik'     => ['required', 'digits:16'],
            'bod'     => ['required', 'date'],
            'ktp'     => ['required', 'image:jpeg,png,jpg,gif,svg|max:2048']
        ]);

        if($validated->fails()) {
            return response()->json([
                'massage' => 'Data tidak valid',
                'data' => $validated->errors()
            ], 500);
        }

        $uploadFolder = 'ktp_uploads';
        $image = $request->file('file');
        $extension = $image->getClientOriginalExtension();
        $fileName = 'ktp-' . now()->format('Y-m-d') . '.' . $extension;
        $image_uploaded_path = $image->storeAs($uploadFolder, $fileName, 'public');

        try {

            $validated = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone,
                'id_role' => 3,
                'is_active' => 0,
                'created_at' => now(),
                'updated_at' => now(),

            ]);

            $validated = Member::create([
                'id_user' => $validated->id,
                'address' => $request->address,
                'nik' => $request->nik,
                'bod' => $request->bod,
                'foto_ktp' => $image_uploaded_path,
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
