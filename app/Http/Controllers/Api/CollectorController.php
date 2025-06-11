<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LoanResource;
use App\Http\Resources\MemberResource;
use App\Models\Collector;
use App\Models\Loan;
use App\Models\Member;
use App\Models\MemberCollector;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

use Illuminate\Http\Request;


class CollectorController extends Controller
{
    public function index() {
        $user = auth()->user();
        $collector = $user->collector;

        return response()->json([
            'message' => 'success',
            'data' => $collector
        ], 200);
    }
    public function getMember(Request $request) {
        $user = auth()->user();
        $collector = $user->collector;

        $search = $request->input('search');

        $relasi = MemberCollector::with('member.user')
            ->where('id_collector', $collector->id)
            ->when($search, function ($query, $search) {
                $query->whereHas('member.user', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%");
                });
            })
            ->get();

        if($relasi->isNotEmpty()) {
            return response()->json([
                'message' => 'success',
                'data' => MemberResource::collection($relasi)
            ], 200);
        }

        return response()->json([
            'message' => 'data not found',
        ], 200);

    }

    // data pinjaman anggota
    public function memberLoan() {
        $user = auth()->user();
        $collector = $user->collector;

        $relasi = MemberCollector::with([
            'member.user',
            'member.loan'
            ])->where('id_collector', $collector->id)
            ->get();


        if($relasi->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada anggota dengan pinjaman',
            ], 200);
        }

        $data = $relasi->map(function ($item) {
            return (new LoanResource($item))->forCollector(request());
        });


        return response()->json([
            'status' => 'success',
            'message' => 'Data pinjaman anggota',
            'data' => $data
        ], 200);
    }

    public function detailMember($id) {
        $member = Member::with('user')->find($id);

        $data = [
            'name' => $member->user->name,
            'phone' => $member->user->phone_number,
            'address' => $member->address
        ];
        return response()->json([
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    // riwayat
    public function history() {
        $user = auth()->user();
        $collector = $user->collector;

        $transaksi = Transaction::with('member.user', 'loan', 'installment', 'deposit')
            ->where('id_collector', $collector->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalSetoran = 
}
