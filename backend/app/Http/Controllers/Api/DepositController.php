<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Transaction;

use Illuminate\Http\Request;

class DepositController extends Controller
{
    // melihat data simpanan (member)
    public function getDeposit() {
        $user = auth()->user();


        $member = $user->member->id;

        $sWajib = Deposit::where('id_member', $member)
            ->where('jenis_simpanan', 'wajib')
            ->get();
        $sSukarela = Deposit::where('id_member', $member)
            ->where('jenis_simpanan', 'sukarela')
            ->get();
        $sPokok = Deposit::where('id_member', $member)
            ->where('jenis_simpanan', 'pokok')
            ->get();


        return response()->json([
            'success' => true,
            'data' => [
                'id' => $member,
                'simpanan_wajib' => $sWajib,
                'simpanan_sukarela' => $sSukarela,
                'simpanan_pokok' => $sPokok
            ]
        ]);
    }

    // tambah simpanan (kolektor)
    public function saveDeposit(Request $request) {
        // $user = auth()->user();

        $validation = $request->validate([
            'id_member' => 'required',
            'jenis_simpanan' => 'required|in:wajib,pokok,sukarela',
            'nominal' => 'required',
            'tgl_simpan' => 'required',
        ]);

        // cek apakah deposit sudah ada
        $deposit = Deposit::where('id_member', $request->id_member)
            ->where('jenis_simpanan', $request->jenis_simpanan)
            ->first();

        // simpan ke tb_transactions
        $simpanan = Transaction::create([
            'id_collector' => $request->id_collector, //ini nanti ganti jadi $user->collector->id
            'id_anggota' => $request->id_member,
            'id_deposit' => $deposit->id,
            'tipe_transaksi' => 'kredit',
            'tgl_transaksi' => $request->tgl_simpan,
            'jumlah' => $request->nominal,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
        'status'  => true,
        'message' => 'Data simpanan berhasil diproses.',
        'data'    => $deposit,
        'data 2'  => $simpanan
    ]);

    }
}
