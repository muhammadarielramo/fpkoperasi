<?php

namespace App\Http\Controllers\Web;

use App\Exports\CashFlowExport;
use App\Http\Controllers\Controller;
use App\Models\CashFlow;
use Carbon\Carbon;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;


class CashFlowController extends Controller
{
    public function showKas(Request $request) {

        // detail kas
        $totalIn = CashFlow::cashIn()->sum('nominal');
        $totalOut = CashFlow::cashOut()->sum('nominal');
        $saldo = $totalIn - $totalOut;

        // request
        $cashIn = null;


        $query = CashFlow::orderBy('created_at', 'desc');


        // filter tanggal
        if ($request->has('date') && !empty($request->date)) {
            $date = $request->input('date');

            // Cek apakah formatnya hanya bulan dan tahun (YYYY-MM)
            if (preg_match('/^\d{4}-\d{2}$/', $date)) {
                $query->whereDate('transaction_date', 'like', $date . '%'); // match bulan dan tahun
            }
            // Jika formatnya tanggal lengkap (YYYY-MM-DD)
            elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                $query->whereDate('transaction_date', $date); // match tanggal
            }
        }
        else {
            $selectedDate = Carbon::now()->format('Y-m-d');
            $query->whereDate('transaction_date', $selectedDate);
        }

        // filter cash_in
        if($cashIn == 1) {
            $query->where('is_cash_in', true);
        }

        $transactions = $query->paginate(10);

        // dd($transactions->toArray());

        return view('admin.riwayat.kasIndex', compact('transactions', 'totalIn', 'totalOut', 'saldo'));
    }

    public function showCreate() {
        return view('admin.riwayat.create');
    }

    public function create(Request $request) {
        $validate = Validator::make($request->all(),[
            'transaction_date' => 'required|date',
            'nominal' => 'required|numeric',
            'is_cash_in' => 'required|boolean',
            'type' => 'required|string',
            'description' => 'required|string',
        ]);


        if($validate->fails()) {
            return back()->withErrors($validate);
        }

        $data = [
            'transaction_date' => $request->transaction_date,
            'nominal' => $request->nominal,
            'is_cash_in' => $request->is_cash_in,
            'type' => $request->type,
            'description' => $request->description,
            'created_by' => auth()->user()->id
        ];

        try {
            CashFlow::create($data);
            return redirect()->route('history.daily')->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data.']);
        }
    }

    public function export(Request $request) {
        $query = CashFlow::query();

        if ($request->has('date') && !empty($request->date)) {
            $date = $request->input('date');

            // Cek apakah formatnya hanya bulan dan tahun (YYYY-MM)
            if (preg_match('/^\d{4}-\d{2}$/', $date)) {
                $query->whereDate('transaction_date', 'like', $date . '%'); // match bulan dan tahun
            }
            // Jika formatnya tanggal lengkap (YYYY-MM-DD)
            elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                $query->whereDate('transaction_date', $date); // match tanggal
            }
        }

        $data = $query->orderBy('transaction_date')->get();


        return Excel::download(new CashFlowExport($data), 'laporan_cash_flow.xlsx');
        }
}
