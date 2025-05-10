<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Http\Controllers\Controller;

class MemberController extends Controller
{
    public function getDatas() {
        $members = Member::with('user')
            ->whereHas('user', function($query) {
            $query->where('is_active', 1);
            })->get();

        return view('admin.data-anggota')->with('members',$members);
    }

    public function destroy($id) {
        $user = Member::findOrFail($id);

        $user->user->update([
            'is_active' => 0,
            'updated_at' => now(),
        ]);

        return redirect()->route('tabel-anggota');
    }

    public function edit($id) {
        $member = Member::findOrFail($id);
        return view();
    }

    public function update() {

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
}
