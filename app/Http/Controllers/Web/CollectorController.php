<?php

namespace App\Http\Controllers\Web;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Collector;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberCollector;
use App\Models\User;
use App\Models\ViewMemberCollector;
use Collator;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\Pagination\Paginator;

class CollectorController extends Controller
{
    public function getDatas(Request $request)  {
        $search = $request->get('search');

        $collectors = Collector::with('user')
            ->whereHas('user', function ($query) use ($search) {
                $query->where('is_active', 1)
                    ->when($search, function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            })
            ->paginate(20);

        return view('admin.kolektor.index', [
            'collectors' => $collectors,
            'search' => $search, // jika ingin tampilkan di form pencarian
        ]);

    }

    public function create() {
        return view('admin.kolektor.add');
    }

    public function store(Request $request){

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_number' => 'required|string|max:20',
            'username' => 'required|string|max:50',
            'password' => 'required|string|min:8',
        ]);

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
                'updated_at' => now(),
            ]);


            // Simpan data kolektor
            Collector::insert([
                'id_user' => $user->id,
                'status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('admin.data-kolektor');

        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function edit($id) {
        $kolektor = Collector::findOrFail($id);
        return view('admin.kolektor.edit', ['kolektor'=>$kolektor]);
    }

    public function update(Request $request, $id)  {

        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone_number' => 'nullable|string|max:20',
            'status' => 'nullable',
            'username' => 'nullable|string|max:50',
        ]);


        // update tabel user
        $kolektor = Collector::findOrFail($id);

        if($request->status == 'Nonaktif') {
            $kolektor->user->update([
                'is_active' => 0,
            ]);
        }

        $kolektor->user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'username' => $request->username,
            'updated_at' => now(),
        ]);

        // update tabel kolektor
        $kolektor->update([
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.data-kolektor');
    }


    public function destroy($id) {
        $kolektor = Collector::findOrFail($id);

        try {
            $kolektor->user->update([
                'is_active' => 0,
                'updated_at' => now(),
            ]);
        } catch (Exception $e) {
            dd($e->getMessage());
        }


        $kolektor->update([
            'status' => 'Non Aktif',
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.data-kolektor');
    }

    public function detailKolektor($id) {
        $collector = Collector::with('user')->findOrFail($id);

        $members = MemberCollector::with('member.user')->where('id_collector', $id)->get();
        // dd($members->toArray());
        return view('admin.kolektor.info', compact('collector', 'members'));
    }
}
