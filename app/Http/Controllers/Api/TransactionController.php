<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\InstallmentResource;
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
        }

        return response()->json([
            'message' => 'Transaksi berhasil ditemukan',
            'data' => $transactions->values(),
            ], 200);
        }
    }

    public function detail ($id) {
        $user = auth()->user();
        $member = $user->member;

        $transactions = $member->transactions()->with('installments.loan')->get();

            $transaction = $transactions->where('id', $id)->first();
            if($transaction) {

                $instalments = $transaction->installments;
                return response()->json([
                    'message' => 'Transaksi berhasil ditemukan',
                    'data' => new InstallmentResource($instalments),
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Transaksi tidak ditemukan',
                ], 200);
            }


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
