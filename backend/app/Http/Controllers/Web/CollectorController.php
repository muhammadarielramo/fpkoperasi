<?php

namespace App\Http\Controllers\Web;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Collector;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\User;
use App\Models\ViewMemberCollector;
use Illuminate\Contracts\View\View;

class CollectorController extends Controller
{
    public function getDatas()  {
        $collectors = Collector::with('user')->get();
        return view('admin.data-kolektor')->with('collectors',$collectors);
    }

    public function create() {
        return view('admin.tambah-kolektor');
    }

    public function store(Request $request){
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|email|unique:users,email',
        //     'username' => 'required|string|max:50|unique:users,username',
        //     'password' => 'required|string|min:8|confirmed',
        //     'phone_number' => 'required|string|max:20',
        //     'status' => 'required',
        // ]);

        try {
            // Simpan user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => bcrypt($request->password),
                'phone_number' => $request->phone_number,
                'id_role' => 2,
                'is_active' => 1,
                'status' => 1,
                'created_at' => now(),
            ]);


            // Simpan data kolektor
            Collector::insert([
                'id_user' => $user->id,
                'status' => $request->status,
                'created_at' => now(),
            ]);

            return redirect()->route('tabel-kolektor')->with('success', 'Kolektor berhasil ditambahkan.');

        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function edit($id) {
        $kolektor = Collector::findOrFail($id);
        return view('admin.edit-kolektor', ['kolektor'=>$kolektor]);
    }

    public function update(Request $request, $id)  {

        // dd($request->all());

        $kolektor = Collector::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            // 'email' => 'required|email|unique:users,email,' . $kolektor->user->id,
            // 'username' => 'required|string|max:50|unique:users,username,' . $kolektor->user->id,
            // 'phone_number' => 'required|string|max:20|unique:users,phone_number,' . $kolektor->user->id,
            // 'status' => 'required|string',
        ]);

        try{
            // update tabel user
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'phone_number' => $request->phone,
                // 'is_active' => $request->is_active ?? 1,
                'status' => $request->status,
                'updated_at' => now(),
            ];

            // update password
            if ($request->filled('password')) {
                $userData['password'] = bcrypt($request->password);
            }

            $kolektor->user->update($userData);

            return redirect()->route('tabel-kolektor');
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }


    public function destroy($id) {
        $kolektor = Collector::findOrFail($id);

        $kolektor->user->update([
            'is_active' => 0,
            'updated_at' => now(),
        ]);

        $kolektor->update([
            'status' => 'Non Aktif',
            'updated_at' => now(),
        ]);

        return redirect()->route('tabel-kolektor');
    }

    public function getAnggota($id) {
        $kolektor = Collector::with('user')->findOrFail($id);
        $name_collector = $kolektor->user->name;
        $member = ViewMemberCollector::where('collector_name', $name_collector)->get();

        return view('admin.kolektor-anggota', compact('member', 'name_collector'));
    }
}
