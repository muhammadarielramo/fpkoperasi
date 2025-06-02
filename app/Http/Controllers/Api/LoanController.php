<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\LoanResource;
use App\Models\Installment;
use App\Models\Loan;
use App\Models\MemberCollector;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    // member
    public function pengajuanPinjaman(Request $request) {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'jumlah_pinjaman' => 'required',
            'tgl_pengajuan' => 'required',
            'tenor' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        // simpan ke db
        $dataPengajuan = [
            'id_member' => $user->member->id,
            'tgl_pengajuan' => $request->tgl_pengajuan,
            'jumlah_pinjaman' => $request->jumlah_pinjaman,
            'tenor' => $request->tenor,
            'status' => 'Diajukan',
            'created_at' => now(),
            'updated_at' => now()
        ];

        try {
            $loan = Loan::create($dataPengajuan);

            return response()->json([
                'success' => true,
                'data' => $loan
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }

    }

    // member
    public function loans () {
        $user = auth()->user();
        $member = $user->member;
        $loans = $member->loan;

        if($loans->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Data pinjaman tidak ditemukan'
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data pinjaman ditemukan',
            'data' => LoanResource::collection($loans)
        ], 200);
    }

    // kolektor
    public function loanPaymentInfo($id_loan) {
        $auth = auth()->user();
        $loan = Loan::find($id_loan);
        $member = $loan->member->user->name;

        return response()->json([
            'success' => true,
            'message' => 'Data pinjaman ditemukan',
            'data' => [
                'member' => $member,
                'loan' => $loan
            ]
        ], 200);
    }

    // kolektor
    public function loanPayment(Request $request, $id_loan) {

    }

    // kolektor
    public function loanInfo($id_loan) {
        $loan = Loan::find($id_loan);

        return response()->json([
            'success' => true,
            'message' => 'Data pinjaman ditemukan',
            'data' => LoanResource::make($loan)
        ], 200);
    }

    // kolektor
    
}
