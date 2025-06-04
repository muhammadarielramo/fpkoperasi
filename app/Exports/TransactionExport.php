<?php

namespace App\Exports;


// use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use App\Models\Transaction;
use Carbon\Carbon;

class TransactionExport implements FromView
{
    protected $date;
    /**
    * @return \Illuminate\Support\Collection
    * @param string $mode  daily|monthly
    * @param string $value  tanggal (Y-m-d) atau bulan (Y-m)
    */
    public function __construct(string $date)
    {
        $this->date = $date;
    }

    public function view(): View
    {
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->date)) {
            // Format harian
            $transactions = Transaction::with('member', 'collector')
                ->whereDate('tgl_transaksi', $this->date)
                ->get();
        } elseif (preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $this->date)) {
            // Format bulanan
            $startDate = $this->date . '-01';
            $endDate = Carbon::parse($startDate)->endOfMonth()->format('Y-m-d');

            $transactions = Transaction::with('member', 'collector')
                ->whereBetween('tgl_transaksis', [$startDate, $endDate])
                ->get();
        } else {
            abort(400, 'Format tanggal tidak valid. Gunakan format YYYY-MM-DD atau YYYY-MM.');
        }

        $totalDebit = $transactions->where('tipe_transaksi', 'debit')->sum('jumlah');
        $totalKredit = $transactions->where('tipe_transaksi', 'kredit')->sum('jumlah');

        return view('exports.transaction', compact('transactions', 'totalDebit', 'totalKredit'));
    }
}
