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

       $selectedDate = $request->input('date')
        ? Carbon::parse($request->input('date'))->format('Y-m-d')
        : Carbon::now()->format('Y-m-d');

        $transactions = Transaction::with('member', 'collector')
            ->whereDate('created_at', $selectedDate)
            ->paginate(20);

        // dd($transactions);
        $totalDebit = $transactions
            ->where('tipe_transaksi', 'debit')
            ->sum('jumlah');
        $totalKredit = $transactions
            ->where('tipe_transaksi', 'kredit')
            ->sum('jumlah');

        return view('admin.riwayat.transaksi', compact('transactions', 'totalDebit', 'totalKredit'));
    }

    public function monthlyHistory(Request $request) {
        $validated = Validator::make($request->all(), [
        'month' => ['required', 'regex:/^\d{4}-(0[1-9]|1[0-2])$/'], // Format: YYYY-MM
        ]);

        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated->errors());
        }

        $month = $request->input('month');
        $startDate = $month . '-01';
        $endDate = date("Y-m-t", strtotime($startDate));

        $transactions = Transaction::with('member', 'collector')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
        $totalDebit = $transactions
            ->where('tipe_transaksi', 'debit')
            ->sum('jumlah');
        $totalKredit = $transactions
            ->where('tipe_transaksi', 'kredit')
            ->sum('jumlah');
        dd($transactions);
    }

    public function export(Request $request) {
        $request->validate([
            'date' => [
                'nullable', // boleh tidak diisi, karena akan default ke hari ini
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                        $fail('Format tanggal tidak valid. Gunakan format YYYY-MM-DD.');
                    }
                },
            ],
        ]);

        return Excel::download(new TransactionExport($request->date), 'riwayat_transaksi.xlsx');
    }
}
