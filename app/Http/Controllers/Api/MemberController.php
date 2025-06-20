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
            'avatar' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'messages' => $validator->messages(),
                ], 422);
        }

        $data = $validator->validated();

        if ($request->hasFile('avatar')) {
            $uploadFolder = 'avatars';
            $image = $request->file('avatar');
            $extension = $image->getClientOriginalExtension();
            $fileName = 'avatar-' . now()->format('Y-m-d') . '.' . $extension;
            $image_uploaded_path = $image->storeAs($uploadFolder, $fileName, 'public');

            // Tambahkan path file ke data yang akan diupdate
            $data['avatar'] = $image_uploaded_path;
        }


        try {
            // update tb user
            $user->update($data);
            

            // update tb memebr
            if (array_key_exists('address', $data)) {
                $member = Member::where('id_user', $user->id)->first();
                if ($member) {
                    $member->update([
                        'address' => $data['address'],
                        'updated_at' => now(),
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'messages' => 'Profile berhasil diupdate',
                'data' => $user->fresh(),
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
