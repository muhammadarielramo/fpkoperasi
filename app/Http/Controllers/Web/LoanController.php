<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function indexPengajuan(Request $request) {
        $pengajuan = Loan::with('member')
            // ->where('status', 'Diajukan')
            ->paginate(20);

        return view('admin.pinjaman.pengajuan', compact('pengajuan'));
    }

    public function index() {
        return redirect('admin.pinjaman.index');
    }

    public function responPengajuan (Request $request, $id) {
        $pengajuan = Loan::findOrFail($id);

        $status = $request->status;
        if($status == 'Diterima') {
            $pengajuan->tgl_persetujuan = now();
        }
        $pengajuan->status = $status;
        $pengajuan->updated_at = now();

        try{
            $pengajuan->save();
            return redirect()->indexPengajuan();
        } catch(\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

    public function history() {
        return view('admin.pinjaman.history');
    }
}
