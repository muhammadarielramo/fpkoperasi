<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Collector;
use App\Models\Member;
use App\Models\Transaction;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Exports\TransactionExport;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

use function Laravel\Prompts\form;

class TransactionController extends Controller
{
    public function deposit() {
        $histories = Transaction::with('member', 'deposit', 'collector')
            ->whereNotNull('id_deposit')
            ->get();
        // dd($histories->toArray());
        return view('admin.simpanan.history', compact( 'histories'));
    }

    public function dailyHistory(Request $request) {

       $dateInput = $request->input('date');

        if ($dateInput) {
            if (preg_match('/^\d{4}-\d{2}$/', $dateInput)) {
                // Format YYYY-MM → ambil range 1 bulan penuh
                $startDate = Carbon::createFromFormat('Y-m', $dateInput)->startOfMonth();
                $endDate = Carbon::createFromFormat('Y-m', $dateInput)->endOfMonth();

                $transactions = Transaction::whereBetween('tgl_transaksi', [$startDate, $endDate])->get();
            } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateInput)) {
                // Format YYYY-MM-DD → ambil satu tanggal
                $selectedDate = Carbon::parse($dateInput)->format('Y-m-d');

                $transactions = Transaction::whereDate('tgl_transaksi', $selectedDate)->get();
            } else {
                // Format tidak valid
                return back()->withErrors(['date' => 'Format tanggal tidak valid. Gunakan YYYY-MM-DD atau YYYY-MM.']);
            }
        } else {
            // Default ke hari ini
            $selectedDate = Carbon::now()->format('Y-m-d');
            $transactions = Transaction::whereDate('tgl_transaksi', $selectedDate)->get();
        }

        // dd($transactions);
        $totalDebit = $transactions
            ->where('tipe_transaksi', 'debit')
            ->sum('jumlah');
        $totalKredit = $transactions
            ->where('tipe_transaksi', 'kredit')
            ->sum('jumlah');

        return view('admin.riwayat.transaksi', compact('transactions', 'totalDebit', 'totalKredit'));
    }

    public function export(Request $request) {
        $request->validate([
            'date' => [
                'nullable', // boleh tidak diisi, karena akan default ke hari ini
                function ($attribute, $value, $fail) {
                    if (
                        !preg_match('/^\d{4}-\d{2}$/', $value) &&
                        !preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                        $fail('Format tanggal tidak valid. Gunakan format YYYY-MM-DD.');
                    }
                },
            ],
        ]);



        return Excel::download(new TransactionExport($request->date), 'riwayat_transaksi.xlsx');
    }
}
