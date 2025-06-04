<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\Paginator;

use Illuminate\Foundation\Providers\FoundationServiceProvider;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function index(){

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
            ->groupBy('members.id', 'users.name')
            ->paginate(10);

        return view('admin.simpanan.index', compact('datas'));
    }

    public function saveDepo() {
        
    }
}
