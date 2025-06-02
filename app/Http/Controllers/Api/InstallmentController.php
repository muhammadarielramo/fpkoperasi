<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InstallmentResource;
use App\Http\Resources\LoanResource;
use App\Models\Installment;
use App\Models\Loan;
use App\Models\MemberCollector;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;



use Illuminate\Http\Request;

class InstallmentController extends Controller
{
    public function loanInstallments() {
        $collector = auth()->user()->collector;
        $members = MemberCollector::where('id_collector', $collector->id)->get();

        // pinjaman setiap member
        $loans = Loan::whereIn('id_member', $members->pluck('id_member'))
            ->where('status', 'Diterima')
            ->with(['installments', 'member'])
            ->get();

        if($loans->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Data pinjaman tidak ditemukan'
            ], 200);
        }

        // tagihan setiap loan
        $dataTagihan = $loans->map(function ($loan) {
            $paidInstallments = $loan->installments()->whereIn('status', ['lunas', 'terlambat'] )->get();
            // $paidInstallments = $loan->installments->where('is_paid', 0);

            if($paidInstallments->isEmpty()) {
                return null;
            }
            $lastPaidInstallment = $paidInstallments->last();

            $sisaHutang = $loan->jumlah_pinjaman - $paidInstallments->sum('besar_ciclan');
            $lastPaidDate = $lastPaidInstallment? $lastPaidInstallment->tgl_pembayaran : null;
            $tanggalJatuhTempo =  $lastPaidDate ? Carbon::parse($lastPaidDate)->addMonth()->format('d F Y') : 'N/A';

            return [
                'id_member' => $loan->id_member,
                'id_loan' => $loan->id,
                'nama_member' => $loan->member->user->name,
                'sisa_hutang' => $sisaHutang,
                'tanggal_jatuh_tempo' => $tanggalJatuhTempo
            ];
        });

        // dd($dataTagihan);

        return response()->json([
            'success' => true,
            'message' => 'Data pinjaman ditemukan',
            'data' => $dataTagihan
        ], 200);

    }

    public function setoran(Request $request, $id_loan) {

        $auth = auth()->user();

        $loan = Loan::find($id_loan);
        $member = $loan->member->user->name;

        $validator = Validator::make($request->all(), [
            'besar_ciclan' => 'required',
            'tgl_pembayaran' => 'required',
            'angsuran_ke' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->messages()
            ],422);
        }

        try {
            // tb installments
            $installment = Installment::create([
                'id_loan' => $id_loan,
                'besar_ciclan' => $request->besar_ciclan,
                'tgl_pembayaran' => $request->tgl_pembayaran,
                'cicilan_ke' => $request->angsuran_ke,
                'status' => $request->status,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // tb transactions
            Transaction::create([
                'id_anggota' => $loan->id_member,
                'id_installment' => $installment->id,
                'tipe_transaksi' => 'kredit',
                'tgl_transaksi' => $request->tgl_pembayaran,
                'jumlah' => $request->besar_ciclan,
                'id_collector' => $auth->collector->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ],422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data pinjaman ditemukan',
            'data' => $installment
        ], 200);

    }
}
