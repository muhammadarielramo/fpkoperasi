<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Loan;
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
            'address' => 'sometimes|string',
            'phone_number' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'messages' => $validator->messages(),
                ], 422);
        }

        try {
            $user->fill($validator->validated());
            $user->updated_at = now();
            $user->save();

            if($request->address) {
                $member = Member::where('id_user', $user->id)->first();
                $member->update([
                    'address' => $request->address,
                    'updated_at' => now(),
                ]);
            }

            return response()->json([
                'success' => true,
                'messages' => 'Profile berhasil diupdate',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'messages' => 'Profile gagal diupdate',
            ], 500);
        }
    }

    public function profile(Request $request) {
        $user = auth()->user();
        $user->load('member');

        return response()->json([
            'success' => true,
            'data' => $user,
        ], 200);
    }

    public function dashboard () {
        $user = auth()->user();
        $member = $user->member;

        $simpananWajib = Deposit::where('id_member', $member->id)
                        ->where('jenis_simpanan', 'wajib')
                        ->sum('total_simpanan');
        $simpananPokok = Deposit::where('id_member', $member->id)
                        ->where('jenis_simpanan', 'pokok')
                        ->sum('total_simpanan');
        $simpananSukarela = Deposit::where('id_member', $member->id)
                        ->where('jenis_simpanan', 'sukarela')
                        ->sum('total_simpanan');
        $totalPinjaman = Loan::where('id_member', $member->id)
                        ->where('status', 'Diterima')
                        ->sum('jumlah_pinjaman');

        return response()->json([
            'success' => true,
            'data' => [
                'simpanan_wajib' => $simpananWajib,
                'simpanan_pokok' => $simpananPokok,
                'simpanan_sukarela' => $simpananSukarela,
                'total_pinjaman' => $totalPinjaman
            ]
        ], 200);
    }
}
