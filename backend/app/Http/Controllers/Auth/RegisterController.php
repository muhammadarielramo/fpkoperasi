<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\Role;
use App\Models\User;
use App\Models\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;


class RegisterController extends Controller
{
      public function show() {
        return view('admin.anggota.pendaftaran');
    }

    public function store(Request $request) {
        // validasi input


        $request -> validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required',
            'alamat' => 'required',
            'bod' => 'required',
            'gender' => 'required',
            'NIK' => 'required',
            'ktp' => 'required',
        ]);

        // dd($request->all());
        $user = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'id_role' => 3,
            'is_active' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $user = User::create($user);

        // try {
        //     $fotoPath = null;
        //     if (
        //         $filename = time() . '_' . $request->ktp;
        //         $fotoPath = $file->storeAs('ktp_uploads', $filename, 'public'); // storage/app/public/ktp_uploads
        //     )}
        // } catch (Exception $e) {
        //     dd($e->getMessage());
        // }

        // dd($fotoPath);
        $member = [
            'id_user' => $user->id,
            'nik' => $request->NIK,
            'foto_ktp' => null,
            'address' => $request->alamat,
            'bod' => $request->bod,
            'gender' => $request->gender,
            'is_verified' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $member = Member::create($member);

    }

    public function getRegisters() {
        $members = Member::with('user')
            ->where('is_verified', 0)
            ->get();

        // dd($members->toArray());

        return view('admin.registers', compact('members'));
    }

    public function tolak($id) {
        $register = Member::findOrFail($id);
        $register->delete();
        $register->user()->delete();

        return redirect()->view('admin.registers');
    }

    public function showRegisters($id) {
        $register = Member::with('user')->findOrFail($id);
        return view('admin.register-data', compact('register'));
    }
}
