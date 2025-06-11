<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\Collector;
use App\Models\Deposit;
use App\Models\Loan;
use App\Models\Member;
use App\Models\Transaction;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $countMember = Member::with('user')
            ->where('is_verified', 1)
            ->whereHas('user', function ($query) {
                $query->where('is_active', 1);
            })
            ->count();

        $countDeposit = Deposit::sum('total_simpanan');
        $loanActive = Loan::where('status', 'Diterima')
            ->sum('jumlah_pinjaman');

        $countCollector = Collector::with('user')
            ->where('status', 'Aktif')
            ->whereHas('user', function ($query) {
                $query->where('is_active', 1);
            })
            ->count();



        // grafik simpan pinjam
        $year = Carbon::now()->year;

        $depositData = Transaction::selectRaw('MONTH(created_at) as month, SUM(jumlah) as total')
            ->whereNotNull('id_deposit')
            ->whereYear('created_at', $year)
            ->groupByRaw('MONTH(created_at)')
            ->pluck('total', 'month')
            ->toArray();

        $loanData = Loan::selectRaw('MONTH(created_at) as month, SUM(jumlah_pinjaman) as total')
            ->whereIn('status', ['Diterima', 'Lunas'])
            ->whereYear('created_at', $year)
            ->groupByRaw('MONTH(created_at)')
            ->pluck('total', 'month')
            ->toArray();

        // Buat array lengkap bulan 1–12
        $labels = [];
        $simpanan = [];
        $pinjaman = [];

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = Carbon::create()->month($i)->format('M');
            $simpanan[] = $depositData[$i] ?? 0;
            $pinjaman[] = $loanData[$i] ?? 0;
        }

        $notifPinjamanBaru = \App\Models\Loan::where('status', 'Diajukan')->count();
        $notifAnggotaBaru = \App\Models\Member::where('is_verified', 0)->count();


        return view('admin.dashboard', compact(
        'countMember',
        'countDeposit',
        'loanActive',
        'countCollector',
        'labels',
        'simpanan',
        'pinjaman',
        'notifPinjamanBaru',
        'notifAnggotaBaru'
        ));
    }
}
