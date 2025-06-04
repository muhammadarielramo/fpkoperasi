<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Collector;
use App\Models\MemberCollector;

class RelationController extends Controller
{
    public function create($id_member) {

        $member = Member::with('user')->findOrFail($id_member);
        $kolektor = Collector::with('user')->where('is_active', true)->get();
        return view('relation.create',compact('member', 'kolektor'));
    }

    public function store(Request $request){
        $request->validate([
            'id_member' => 'required|exists:members,id',
            'id_collector' => 'required|exists:collectors,id',
            ]);

        MemberCollector::create([
        'id_member' => $request->id_member,
        'id_collector' => $request->id_collector,
        'tgl_penugasan' => $request->tgl_penugasan,
         ]);

        return redirect()->route('tabel-anggota')->with('success', 'Relasi berhasil ditambahkan');
    }

    public function showMembers($id) {
        $collector = Collector::with('members.user')->findOrFail($id);

        dd($collector);
    }
}
