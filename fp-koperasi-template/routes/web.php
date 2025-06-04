<?php

use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Web\TransactionController;
use App\Http\Controllers\Web\DepositController;
use App\Http\Controllers\Web\LoanController;
use App\Http\Controllers\Web\CollectorController;
use App\Http\Controllers\Web\MemberController;
use App\Models\MemberCollector;
use App\Models\Role;
use Whoops\Run;
use App\Http\Controllers\Web\RelationController;
use App\Models\Deposit;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Auth;

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
Route::get('/logout', [LoginController::class, 'destroy'])->name('logout');

// dashboard
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function(){
    Route::get('/dashboard', DashboardController::class)->name('dashboard');


    Route::get('/anggota', [MemberController::class, 'getDatas'])->name('data-anggota');
    Route::get('/kolektor', [CollectorController::class, 'getDatas'])->name('data-kolektor');
    Route::get('/pendaftaran-anggota', [RegisterController::class, 'show'])->name('pendaftaran-anggota');
    Route::get('/data', AdminController::class)->name('data-admin');
});

// anggota
Route::group(['prefix' => 'anggota', 'as' => 'anggota.'], function(){
    Route::get('/info/{id}', [MemberController::class, 'detailAnggota'])->name('info');
    Route::delete('/hapus/{id}', [MemberController::class, 'destroy'])->name('hapus');
    Route::get('/edit/{id}', [MemberController::class, 'edit'])->name('edit');
    Route::put('/edit/{id}', [MemberController::class, 'update'])->name('update');
});

// kolektor
Route::group(['prefix' => 'kolektor', 'as' => 'kolektor.'], function(){
    Route::get('/info/{id}', [CollectorController::class, 'detailKolektor'])->name('info');
    Route::get('/edit/{id}', [CollectorController::class, 'edit'])->name('edit');
    Route::put('/edit/{id}', [CollectorController::class, 'update'])->name('update');
    Route::delete('/hapus/{id}', [CollectorController::class, 'destroy'])->name('hapus');
    Route::get('/anggota/{id}', [RelationController::class, 'showMembers'])->name('anggota');
    Route::get('/tambah', [CollectorController::class, 'create'])->name('tambah');
    Route::post('/simpan', [CollectorController::class, 'store'])->name('simpan');
});

// pinjaman
Route::group(['prefix' => 'pinjaman', 'as' => 'pinjaman.'], function(){
    Route::get('/pengajuan', [LoanController::class, 'indexPengajuan'])->name('pengajuan');
    Route::get('/data',[LoanController::class, 'index'])->name('index');
    Route::get('/history',[LoanController::class, 'history'])->name('history');
});

// simpanan
Route::group(['prefix' => 'simpanan', 'as' => 'simpanan.'], function(){
    Route::get('/data', [DepositController::class, 'index'])->name('index');
    Route::get('/history',[TransactionController::class, 'deposit'])->name('history');
    Route::get('/history/info/{id}', [TransactionController::class, 'infoHistori'])->name('histori.info');
});









// relasi
Route::get('relasi/tambah/{id}', [RelationController::class, 'create'])->name('relasi.create');
Route::post('relasi/simpan', [RelationController::class, 'store'])->name('relasi.store');




// registrasi

Route::get('/registation/verifikasi/{id}', [RegisterController::class, 'showRegister']);
Route::post('/registation', [RegisterController::class, 'store'])
    ->name('registation.store');
Route::get('registation/verifikasi', [RegisterController::class, 'getRegisters']);
Route::patch('registration/destroy/{id}', [RegisterController::class, 'tolak'])
    ->name('registers.tolak');

Route::get('/simpanan/pengajuan', [LoanController::class, 'getPengajuan']);
Route::post('/simpanan/pengajuan/{id}', [LoanController::class, 'responPengajuan'])
    ->name('pinjaman.respon');



