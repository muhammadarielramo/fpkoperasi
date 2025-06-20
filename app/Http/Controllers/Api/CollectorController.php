<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LoanResource;
use App\Http\Resources\MemberResource;
use App\Http\Resources\TransactionResource;
use App\Models\Collector;
use App\Models\Loan;
use App\Models\Member;
use App\Models\MemberCollector;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

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
<<<<<<< HEAD
=======
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
>>>>>>> backend2
            'data' => $data
        ], 200);
    }

<<<<<<< HEAD
    public function kunjunganHariIni() {
=======
    // riwayat
    public function history(Request $request) {
        $user = auth()->user();
        $collector = $user->collector;

        $dateInput = $request->input('date'); // Untuk tanggal tunggal (YYYY-MM-DD)
        $startDateInput = $request->input('start_date'); // Untuk awal range (YYYY-MM-DD)
        $endDateInput = $request->input('end_date');     // Untuk akhir range (YYYY-MM-DD)

        $query = Transaction::with('member.user')
                            ->where('id_collector', $collector->id)
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

        return response()->json([
            'message' => 'success',
            'data' => TransactionResource::collection($transactions)->resolve()
        ], 200);
>>>>>>> backend2

    }

    public function kunjunganHariIni() {
        $collector = auth()->user()->collector;
        $assignedMemberIds = DB::table('member_collector')
            ->where('id_collector', $collector->id)
            ->pluck('id_member');

        // Ambil data member lengkap + loans + installments
        $members = Member::with(['loan.installments'])
            ->whereIn('id', $assignedMemberIds)
            ->get();

        $hasilKunjungan = [];

        foreach ($members as $member) {
            foreach ($member->loan ?? [] as $loan) {
                $installments = $loan->installments ?? collect();
                $lastInstallment = $installments->sortByDesc('tgl_pembayaran')->first();
                // dd($lastInstallment->toArray());

                if ($lastInstallment) {
                    $nextInstallmentDate = Carbon::parse($lastInstallment->tgl_pembayaran)->addMonth();
                    // dd($nextInstallmentDate);

                    if ($nextInstallmentDate->isToday()) {
                        $hasilKunjungan[] = [
                            'loan_id' => $loan->id,
                            'nama_member' => $member->user->name,
                            'alamat' => $member->address,
                            'batas_pembayawan' => $nextInstallmentDate->format('d F Y'),
                        ];
                    }
                }
            }
        }

        if (empty($hasilKunjungan)) {
            return response()->json([
                'message' => 'Tidak ada kunjungan hari ini',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Kunjungan hari ini ditemukan',
                'data' => $hasilKunjungan,
            ], 200);
        }
    }
}
