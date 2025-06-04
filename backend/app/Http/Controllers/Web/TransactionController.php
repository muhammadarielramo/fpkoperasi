<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function deposit() {
        $histories = Transaction::with('member', 'deposit', 'collector')
            ->whereNotNull('id_deposit')
            ->get();
        // dd($histories->toArray());
        return view('admin.simpanan.history', compact( 'histories'));
    }
}
