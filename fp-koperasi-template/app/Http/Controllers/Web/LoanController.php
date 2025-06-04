<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function indexPengajuan(Request $request) {
        // $pengajuan = Loan::with('member.user')->get();

        return view('admin.pinjaman.pengajuan');
    }

    public function index() {
        return view('admin.pinjaman.index');
    }

    public function responPengajuan (Request $request, $id) {
        $pengajuan = Loan::findOrFail($id);

        $status = $request->input('status');

        $respons = [
            'status' => $status,
            'updated_at' => now(),
            'tgl_persetujuan' => null
        ];

        if($status == 'terima') {
            $respons['tgl_persetujuan'] = now();
        }

        $pengajuan->update($respons);

        try{
            $pengajuan->save();
            return route('dashboard');
        } catch(\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

    public function history() {
        return view('admin.pinjaman.history');
    }
}
