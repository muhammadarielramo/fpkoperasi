<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function pengajuanPinjaman(Request $request) {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'tgl_pengajuan' => 'required',
            'nama_pinjaman' => 'required',
            'jumlah_pinjaman' => 'required',
            'tenor' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        // simpan ke db
        $dataPengajuan = [
            'id_member' => $user->member->id,
            'tgl_pengajuan' => $request->tgl_pengajuan,
            'nama_pinjaman' => $request->nama_pinjaman,
            'jumlah_pinjaman' => $request->jumlah_pinjaman,
            'tenor' => $request->tenor,
            'status' => 'Diajukan',
            'created_at' => now(),
            'updated_at' => now()
        ];

        $loan = Loan::create($dataPengajuan);
        return response()->json([
            'success' => true,
            'data' => $loan
        ], 200);

    }
}
