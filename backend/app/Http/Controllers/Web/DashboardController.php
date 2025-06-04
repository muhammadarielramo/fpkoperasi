<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Member;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $countMembers = Member::with('user')
            ->whereHas('user', function($query) {
            $query->where('is_active', 1);
            })->count();

        $countPengajuan = Loan::where('status', 'diajukan')->count();

        $countRegisters = Member::where('is_verified', 0)->count();

        return view('admin.dashboard', compact('countMembers', 'countPengajuan', 'countRegisters'));
    }
}
