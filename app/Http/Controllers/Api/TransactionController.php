<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\InstallmentResource;
use App\Models\Installment;
use App\Models\Member;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function history(Request $request) {
        $user = auth()->user();
        $member = $user->member;

        $transactions = $member->transactions;

        // dd($request->type);

        if($request->has('type')) {
            $type = $request->type;

            switch ($type) {
            case 'loan':
                $transactions = $transactions->whereNotNull('id_loan');
                break;
            case 'deposit':
                $transactions = $transactions->whereNotNull('id_deposit');
                break;
            case 'installment':
                $transactions = $transactions->whereNotNull('id_installment');
                break;
        }

        if($transactions->isEmpty()) {
            return response()->json([
                'message' => 'Transaksi tidak ditemukan',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Transaksi berhasil ditemukan',
                'data' => $transactions,
                ], 200);
            }
        }

    }

    public function detail($id) {
        $user = auth()->user();
        $member = $user->member;

        // Cari transaksi milik member tersebut dengan ID yang sudah didapat
        $transaction = Transaction::with(['installments'])->find($id);

        if (!$transaction) {
            return response()->json(
                ['message' => 'Transaksi tidak ditemukan.'],
                200);
        }

        $installment_id = $transaction->installments->id;

        $installment = Installment::with([
                            'transaction.loan',
                            'transaction.location'
                        ])->findOrFail($installment_id);

        return response()->json([
            'message' => 'Transaksi berhasil ditemukan',
            'data' => new InstallmentResource($installment),
        ], 200);



        // // PERBAIKAN: Cek apakah relasi 'installments' ada dan tidak kosong
        // if ($transaction->installments && !$transaction->installments->isNotEmpty()) {
        //     // Jika ada, ambil angsuran pertama
        //     $installment = $transaction->installments->first();

        //     // Berikan SATU objek tunggal ke dalam resource, bukan koleksi.
        //     return response()->json([
        //         'message' => 'Transaksi berhasil ditemukan',
        //         'data' => new InstallmentResource($installment),
        //     ], 200);
        // } else {
        //     // Jika tidak ada data angsuran terkait sama sekali,
        //     // kembalikan data transaksi dasarnya saja
        //      return response()->json([
        //         'message' => 'Detail angsuran tidak ditemukan untuk transaksi ini.',
        //         'data' => $transaction
        //     ], 200);
        // }
    }

    // simpanan dari kolektor
    public function saveDeposit(Request $request, $id) {
        $user = auth()->user();
        $collector = $user->collector;

        $validator = Validator::make($request->all(), [
            'id_member' => 'required',
            'tgl_simpanan' => 'required|date',
            'jenis_simpanan' => 'required|string',
            'nominal' => 'required|numeric|min:1000',
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                  'errors' => $validator->errors()
            ], 200);
        }

        $exists = DB::table('deposits')
            ->where('id_member', $request->id_member)
            ->where('jenis_simpanan', $request->jenis_simpanan)
            ->exists();

        if($exists) {

        }

    }
}
