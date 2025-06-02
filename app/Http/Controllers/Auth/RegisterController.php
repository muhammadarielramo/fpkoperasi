<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\Role;
use App\Models\User;
use App\Models\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;


class RegisterController extends Controller
{
      public function show(Request $request) {
        $registers = User::where('id_role', 3)
            ->whereHas('member', function ($query) {
                $query->where('is_verified', 0);
            })
            ->paginate(10);
        // dd($registers->toArray());

        return view('admin.anggota.registers', compact('registers'));
    }

    public function terima($id) {
        $register = User::findOrFail($id);
        $email = $register->email;

        $data = [
            'name' => $register->name,
            'link' => route('verifikasi.halaman', ['id' => $register->id]),
        ];

        try {
            Mail::to($email)->send(new SendMail($data));
            return 'Email sent successfully!';
        } catch (Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
            return 'Failed to send email: ' . $e->getMessage();
        }
    }

    public function tolak($id) {
        $register = User::findOrFail($id);
        Member::where('id_user', $register->id)->delete();
        $register->delete();

        return redirect()->back();
    }

    public function verifPage($id) {
        return view('admin.anggota.verifikasi', compact('id'));
    }

    public function verifikasi(Request $request, $id) {
        $register = User::findOrFail($id);
        $register->update([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
            'is_active' => 1,
            'updated_at' => now()
        ]);

        $member = $register->member;
        $member->update([
            'is_verified' => 1,
            'updated_at' => now()
        ]);
    }
}
