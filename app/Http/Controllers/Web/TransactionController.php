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
    public function deposit(Request $request)
    {
        $query = Transaction::with('member', 'deposit', 'collector')
            ->whereNotNull('id_deposit');

        if ($request->has('date') && !empty($request->date)) {
            $date = $request->input('date');

            // Cek apakah formatnya hanya bulan dan tahun (YYYY-MM)
            if (preg_match('/^\d{4}-\d{2}$/', $date)) {
                $query->whereDate('tgl_transaksi', 'like', $date . '%'); // match bulan dan tahun
            }
            // Jika formatnya tanggal lengkap (YYYY-MM-DD)
            elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                $query->whereDate('tgl_transaksi', $date); // match tanggal
            }
        }

        $histories = $query->get();

        return view('admin.simpanan.history', compact('histories'));
    }


    public function dailyHistory(Request $request) {

               $dateInput = $request->input('date'); // Untuk tanggal tunggal (YYYY-MM-DD)
        $startDateInput = $request->input('start_date'); // Untuk awal range (YYYY-MM-DD)
        $endDateInput = $request->input('end_date');     // Untuk akhir range (YYYY-MM-DD)

        $query = Transaction::with('member.user')
                            ->orderBy('created_at', 'desc');

        if ($startDateInput && $endDateInput) {
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $startDateInput) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $endDateInput)) {
                return back()->withErrors(['date_range' => 'Format tanggal mulai atau tanggal akhir tidak valid. Gunakan YYYY-MM-DD.']);
            }
            try {
                $startDate = Carbon::parse($startDateInput)->startOfDay();
                $endDate = Carbon::parse($endDateInput)->endOfDay();
                if ($startDate->greaterThan($endDate)) {
                    return back()->withErrors(['date_range' => 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir.']);
                }

                $query->whereBetween('tgl_transaksi', [$startDate, $endDate]);

            } catch (\Exception $e) {
                return back()->withErrors(['date_range' => 'Terjadi kesalahan dalam memproses rentang tanggal.']);
            } } elseif ($dateInput) {
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateInput)) {
                return back()->withErrors(['date' => 'Format tanggal tidak valid. Gunakan YYYY-MM-DD.']);
            }

            try {
                $selectedDate = Carbon::parse($dateInput)->format('Y-m-d');
                $query->whereDate('tgl_transaksi', $selectedDate);
            } catch (\Exception $e) {
                return back()->withErrors(['date' => 'Terjadi kesalahan dalam memproses tanggal tunggal.']);
            }

        } else {
            $selectedDate = Carbon::now()->format('Y-m-d');
            $query->whereDate('tgl_transaksi', $selectedDate);
        }

        $transactions = $query->get();

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
