<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Http\Controllers\Controller;
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

    public function register(Request $request) {
        $request->validate([
            'name' => 'required',
            'no_hp' => 'required',
            'email' => 'required|email',
            'username' => 'required',
            'password' => 'required',
            'address' => 'required',
        ]);
    }

    public function detailAnggota($id){
        $member = Member::with('user')->findOrFail($id);
        return view('admin.anggota.info', compact('member'));
    }
}
