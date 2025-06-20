<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Transaction;
use App\Models\TransactionLocation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

use function PHPUnit\Framework\isNull;

class DepositController extends Controller
{
    // melihat data simpanan (member)
    public function getDeposit() {
        $user = auth()->user();
        $member = $user->member;
        $deposits = $member->deposit;

        if($deposits->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Data tidak ditemukan',
            ], 200);
        }

        $sWajib = $deposits->where('jenis_simpanan', 'wajib')->sum('total_simpanan');
        $sSukarela = $deposits->where('jenis_simpanan', 'sukarela')->sum('total_simpanan');
        $sPokok = $deposits->where('jenis_simpanan', 'pokok')->sum('total_simpanan');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $member->user->name,
                'total_simpanan' => $sWajib + $sSukarela + $sPokok,
                'simpanan' => $deposits
            ]
        ], 200);
    }

    // tambah simpanan (kolektor)
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

        $deposit = Deposit::where('id_member', $id)
            ->where('jenis_simpanan', $request->jenis_simpanan)
            ->first();
        // dd($deposit);


        DB::beginTransaction();

        try {
            // simpan ke deposits
            if(is_null($deposit)) {
                $deposit = Deposit::create([
                    'id_member' => $request->id_member,
                    'updated_at' => $request->tgl_simpanan,
                    'created_at' => now(),
                    'jenis_simpanan' => $request->jenis_simpanan,
                    'total_simpanan' => $request->nominal,
                ]);
            } else {
                $deposit->update([
                    'id_member' => $request->id_member,
                    'updated_at' => $request->tgl_simpanan,
                    'jenis_simpanan' => $request->jenis_simpanan,
                    'total_simpanan' => $deposit->total_simpanan + $request->nominal,
                ]);
            }

            // simpan ke transaction
            $transaction =Transaction::create([
                'id_anggota' => $request->id_member,
                'id_deposit' => $deposit->id,
                'tipe_transaksi' => 'kredit',
                'tgl_transaksi' => $request->tgl_simpanan,
                'jumlah' => $request->nominal,
                'id_collector' => $collector->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // simpan ke transaction_location
            $transactionLoc = TransactionLocation::create([
                'id_transaction' => $transaction->id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'created_at' => now(),
                'collector_id' => $collector->id
            ]);

            $transaction->location()->save($transactionLoc);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data simpanan dan transaksi berhasil disimpan',
                'data' => $transaction
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Data gagal disimpan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
