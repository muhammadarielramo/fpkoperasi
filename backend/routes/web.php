<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Web\CollectorController;
use App\Http\Controllers\Web\MemberController;
use App\Models\MemberCollector;
use App\Models\Role;
use Whoops\Run;
use App\Http\Controllers\RelationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/dashboard', function () {
    return view('admin/dashboard');
})->name('dashboard');
Route::get('/logout', [LoginController::class, 'destroy'])->name('logout');


// anggota
Route::get('/tabel-anggota', [MemberController::class, 'getDatas'])->name('tabel-anggota');
Route::get('/anggota/edit/{id}', [MemberController::class, 'edit']);
Route::put('/anggota/edit/{id}', [MemberController::class, 'update'])->name('member.update');
Route::delete('/anggota/hapus/{id}', [MemberController::class, 'destroy'])->name('member.destroy');

// relasi
Route::get('relasi/tambah/{id}', [RelationController::class, 'create'])->name('relasi.create');
Route::post('relasi/simpan', [RelationController::class, 'store'])->name('relasi.store');


// kolektor
Route::get('/tabel-kolektor', [CollectorController::class, 'getDatas'])->name('tabel-kolektor');
Route::get('/kolektor/tambah', [CollectorController::class, 'create'])->name('kolektor.create');
Route::post('/kolektor/simpan', [CollectorController::class, 'store'])->name('kolektor.store');
Route::get('/kolektor/edit/{id}', [CollectorController::class, 'edit']);
Route::put('/kolektor/edit/{id}', [CollectorController::class, 'update'])->name('kolektor.update');
Route::delete('/kolektor/hapus/{id}', [CollectorController::class, 'destroy'])->name('kolektor.destroy');
Route::get('/kolektor/{id}/anggota/', [CollectorController::class, 'getAnggota'])->name('kolektor.anggota');
