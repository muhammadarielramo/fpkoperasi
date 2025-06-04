<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Doctrine\DBAL\Schema\Index;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function indexPengajuan(Request $request) {
        $query = Loan::with('member.user');

        // Filter berdasarkan status jika tersedia
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Pencarian berdasarkan nama anggota
        if ($request->filled('search')) {
            $query->whereHas('member.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Urutkan berdasarkan tanggal pengajuan terbaru
        $pengajuan = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.pinjaman.pengajuan', compact('pengajuan'));
    }

    public function index(Request $request) {

        $query = Loan::with('member.user')
            ->where('status', '!=', 'Ditolak');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;

            $query->whereHas('member.user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        $loans = $query->get();

        return view('admin.pinjaman.index', compact('loans'));
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

    public function detailPinjaman ($id) {
        $loan = Loan::with('member', 'installments')->findOrFail($id);

        return view('admin.pinjaman.detail', compact('loan'));
    }

    public function lunas ($id) {
        $loan = Loan::findOrFail($id);
        $loan->status = 'Lunas';
        $loan->save();

        return redirect()->route('pinjaman.index');
    }
}
