<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Http\Controllers\Controller;
use App\Models\Collector;
use App\Models\MemberCollector;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Console\View\Components\Info;

class MemberController extends Controller
{
    public function getDatas(Request $request) {
        $members = Member::with('user')
            ->whereHas('user', function($query) {
            $query->where('is_active', 1);
            });

        // pencarian
        if($request->has('search')) {
            $search = $request->search;
            $members->where(function ($q) use ($search) {
            $q->where('id', 'like', "%{$search}%")
              ->orWhereHas('user', function ($uq) use ($search) {
                  $uq->where('name', 'like', "%{$search}%");
                });
            });
        }

        $members = $members->paginate(20);

        return view('admin.anggota.index')->with('members',$members);
    }

    public function destroy($id) {
        $member = Member::findOrFail($id);


        // update tb user
        $member->user->update([
            'is_active' => 0,
            'updated_at' => now(),
        ]);

        // update tb member
        $member->update([
            'updated_at' => now(),]);

        return redirect()->route('admin.data-anggota');
    }

    public function edit($id) {
        $member = Member::findOrFail($id);
        return view('admin.anggota.edit', compact('member'));
    }

    public function update(Request $request, $id) {
        $validated = $request->validate([
            'name' => 'nullable',
            'email' => 'nullable|email',
            'phone_number' => 'nullable',
            'address' => 'nullable'
        ]);

        $member = Member::findOrFail($id);

        $member->update([
            'address' => $request->address,
            'updated_at' => now(),
        ]);

        $member->user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'updated_at' => now(),
        ]);

        return $this->getDatas($request);
    }

    public function registerForm() {
        return view('member.register');
    }


    public function detailAnggota($id){
        $member = Member::with('user', 'loan', 'deposit')->findOrFail($id);
        $kolektor = MemberCollector::with('collector')
            ->where('id_member', $id)
            ->whereHas('collector', function ($query) {
                $query->where('is_active', true);
            })
            ->first() ?? '-';

        $simpananWajib = $member->deposit->where('jenis_simpanan', 'wajib')->sum('total_simpanan') ?? 0;
        $simpananPokok = $member->deposit->where('jenis_simpanan', 'pokok')->sum('total_simpanan') ?? 0;
        $simpananSukarela = $member->deposit->where('jenis_simpanan', 'sukarela')->sum('total_simpanan') ?? 0;
        $totalSimpanan = $simpananWajib + $simpananPokok + $simpananSukarela;

        $pastLoans = $member->loan->where('status', 'Lunas') ?? '-';
        $loans = $member->loan->whereIn('status', ['Diterima', 'Berlangsung'])->first();
        // dd($loans);
        // dd($pastLoans);

        return view('admin.anggota.info',
            compact('member',
                            'kolektor',
                                    'simpananWajib',
                                    'simpananPokok',
                                    'simpananSukarela',
                                    'totalSimpanan',
                                    'pastLoans',
                                    'loans'));
    }

    public function showAddMember($id) {
        $member = Member::with('user')->findOrFail($id);
        $kolektor = Collector::with('user')
            ->where('status', 'Aktif')
            ->whereHas('user', function ($query) {
                $query->where('is_active', 1);
            })
            ->get();
        return view('admin.anggota.add-kolektor', compact('member', 'kolektor'));
    }

    public function saveAddMember(Request $request, $id) {

        $request->validate([
            'collector_id' => 'required|exists:users,id',
            'tgl_penugasan' => 'required',
        ]);

        $member = Member::findOrFail($id);
        $memberId = $member->id;
        $collectorId = $request->collector_id;

        $existing = MemberCollector::where('id_member', $memberId)->first();

        if ($existing) {
            $existing->update([
                'id_collector' => $collectorId,
                'tgl_penugasan' => $request->tgl_penugasan,
                'updated_at' => now(),
            ]);
            return redirect()->route('admin.data-anggota')->with('success', 'Kolektor berhasil diperbarui.');
        } else {
            MemberCollector::create([
                'id_collector' => $collectorId,
                'id_member' => $memberId,
                'tgl_penugasan' => $request->tgl_penugasan,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


    return redirect()->route('admin.data-anggota')->with('success', 'Kolektor berhasil ditambahkan atau diperbarui.');
    }

    public function showAddMember($id) {
        $member = Member::with('user')->findOrFail($id);
        $kolektor = Collector::with('user')
            ->where('status', 'Aktif')
            ->whereHas('user', function ($query) {
                $query->where('is_active', 1);
            })
            ->get();
        return view('admin.anggota.add-kolektor', compact('member', 'kolektor'));
    }

    public function saveAddMember(Request $request, $id) {

        $request->validate([
            'collector_id' => 'required|exists:users,id',
            'tgl_penugasan' => 'required',
        ]);

        $member = Member::findOrFail($id);
        $memberId = $member->id;
        $collectorId = $request->collector_id;

        $existing = MemberCollector::where('id_member', $memberId)->first();

        if ($existing) {
            $existing->update([
                'id_collector' => $collectorId,
                'tgl_penugasan' => $request->tgl_penugasan,
                'updated_at' => now(),
            ]);
            return redirect()->route('admin.data-anggota')->with('success', 'Kolektor berhasil diperbarui.');
        } else {
            MemberCollector::create([
                'id_collector' => $collectorId,
                'id_member' => $memberId,
                'tgl_penugasan' => $request->tgl_penugasan,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


    return redirect()->route('admin.data-anggota')->with('success', 'Kolektor berhasil ditambahkan atau diperbarui.');
    }
}
