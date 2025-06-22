<?php

namespace App\Http\Controllers\Web;

use App\Events\LoanRespon;
use App\Http\Controllers\Controller;
use App\Models\Installment;
use App\Models\Loan;
use App\Models\Notification;
use App\Models\Transaction;
use Doctrine\DBAL\Schema\Index;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


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
        $loan = Loan::with('member.user')->findOrFail($id);


        $status = $request->status;

        if($status == 'Diterima') {
            $loan->tgl_persetujuan = now();}

        $loan->status = $status;
        $loan->updated_at = now();


        try{
            // update tabel Loan
            $loan->save();

            $recipientUser = $loan->member->user;
            // dd($recipientUser);

            // kirim notif
            if($status == 'Diterima') {
                event(new LoanRespon($loan, $recipientUser, 'approved'));
            } else {
                event(new LoanRespon($loan, $recipientUser, 'rejected'));
            }

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

    public function addPaymentShow($id) {
        $loan = Loan::findOrFail($id);
        $data = [
            'nama' => $loan->member->user->name,
            'id' => $loan->id,
        ];
        return view('admin.pinjaman.add', compact('data'));
    }

    public function addPayment(Request $request, $id){
        $request->validate([
            'besar_ciclan' => 'required|numeric',
            'tgl_pembayaran' => 'required|date',
            'cicilan_ke' => 'required|numeric',
            'status' => 'required',
        ]);

        $loan = Loan::with('member')->findOrFail($id);

        try {
            // simban tb installments
            $installment = Installment::create([
                'id_loan' => $id,
                'tgl_pembayaran' => $request->tgl_pembayaran,
                'cicilan_ke' => $request->cicilan_ke,
                'besar_ciclan' => $request->besar_ciclan,
                'status' => $request->status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            //simpan tb transaksi
            Transaction::create([
                'id_anggota' => $loan->id_member,
                'id_loan' => $id,
                'id_installment' => $installment->id,
                'tipe_transaksi' => 'kredit',
                'tgl_transaksi' => $request->tgl_pembayaran,
                'jumlah' => $request->besar_ciclan,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('pinjaman.index');

        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors([
                'general_error' => 'Terjadi kesalahan tak terduga. Mohon coba lagi.'
            ]);
        }
    }
}
