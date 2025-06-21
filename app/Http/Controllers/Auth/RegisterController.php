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
use Illuminate\Support\Str;


class RegisterController extends Controller
{
      public function show(Request $request) {

        $search = $request->input('search');
        $registers = User::where('id_role', 3)
            ->whereHas('member', function ($query) {
                $query->where('is_verified', 0);
            })
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.anggota.registers', compact('registers'));
    }

    public function terima($id) {
        $register = User::findOrFail($id);
        $email = $register->email;

        $token = Str::random(60);
        $register->update([
            'kode_otp' => $token
        ]);

        $data = [
            'name' => $register->name,
            'link' => route('verifikasi.halaman', ['id' => $register->kode_otp]),
        ];
        try {
                Mail::to($email)->send(new SendMail($data));
                return redirect()->back()->with('success', 'Registrasi Diterima. Email Berhasil Terkirim');
        } catch (\Exception $e) {
                Log::error('Email sending failed: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    public function tolak($id) {
        User::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Registrasi Ditolak');
    }

    public function verifPage($id) {
        return view('admin.anggota.verifikasi', compact('id'));
    }

    public function verifikasi(Request $request, $id) {

        // ambil user berdasarkan token
        $register = User::where('kode_otp', $id)->first();
        if(!$register) {
            return redirect()->back()->with('error', 'Token tidak valid.');
        }

        if($register->email_verified_at) {
            return redirect()->back()->with('error', 'Akun sudah diverifikasi.');
        }

        // token valid
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

        return redirect()->back()->with('success', 'Registrasi berhasil. Silakan Login di Aplikasi');
    }

    public function confirmEmailVerification($token) {
        $user = User::where('kode_otp', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'Token tidak valid'], 400);
        }

        try {
            $user->update([
                'is_active' => 1,
                'updated_at' => now(),
                'email_verified_at' => now(),
            ]);
            return response()->json(['message' => 'Email berhasil aktivasi'],200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal aktivasi email',
                'error' => $e->getMessage()
            ], 500);
        }

    }
}
