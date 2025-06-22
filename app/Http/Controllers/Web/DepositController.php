<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Member;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Foundation\Providers\FoundationServiceProvider;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function index(Request $request) {

        $search = $request->input('search');

        $datas = DB::table('members')
            ->leftJoin('deposits', 'members.id', '=', 'deposits.id_member')
            ->join('users', 'members.id_user', '=', 'users.id')
            ->select(
                'members.id as id_member',
                'users.name',
                DB::raw("SUM(CASE WHEN jenis_simpanan = 'wajib' THEN total_simpanan ELSE 0 END) as total_wajib"),
                DB::raw("SUM(CASE WHEN jenis_simpanan = 'pokok' THEN total_simpanan ELSE 0 END) as total_pokok"),
                DB::raw("SUM(CASE WHEN jenis_simpanan = 'sukarela' THEN total_simpanan ELSE 0 END) as total_sukarela"),
                DB::raw("SUM(total_simpanan) as total_simpanan")
            )
            ->when($search, function ($query, $search) {
                return $query->where('users.name', 'like', '%' . $search . '%');
            })
            ->groupBy('members.id', 'users.name')
            ->paginate(10);

        return view('admin.simpanan.index', compact('datas', 'search'));
    }

    public function saveDepo(Request $request) {
        $request->validate([
            'id' => 'required|numeric',
            'tgl_pembayaran' => 'required|date',
            'nominal' => 'required|numeric',
            'jenis' => 'required',
        ]);

        // find id depo
        $deposit = Deposit::where('id_member', $request->id)
            ->where('jenis_simpanan', $request->jenis)
            ->first();

        try {
            // simpan tb deposit
            if($deposit) {
                $deposit->total_simpanan += $request->nominal;
                $deposit->save();
            } else {
                $deposit = new Deposit();
                $deposit->id_member = $request->id;
                $deposit->jenis_simpanan = $request->jenis;
                $deposit->total_simpanan = $request->nominal;
                $deposit->save();
            }

            // simpan tb transaction
            Transaction::create([
                'id_anggota' => $request->id,
                'id_deposit' => $deposit->id,
                'tipe_transaksi' => 'kredit',
                'tgl_transaksi' => $request->tgl_pembayaran,
                'jumlah' => $request->nominal,
                'created_at' => now(),
                'updated_at' => now(),
            ]);


            return redirect()->route('simpanan.index')->with('success', 'Data berhasil disimpan');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function addShow($id) {
        $member = Member::findOrFail($id);


        return view('admin.simpanan.add', compact('member'));
    }
}
