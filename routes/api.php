<?php

use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\CollectorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\DepositController;
use App\Http\Controllers\Api\InstallmentController;
use App\Http\Controllers\Api\LoanController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\SlipController;
use App\Http\Controllers\Api\TransactionController;
use App\Models\Collector;
use App\Models\Installment;
use App\Models\Member;
use Illuminate\Auth\Events\Login;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// auth
Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/login', [LoginController::class, 'login']) ->name('login');
Route::middleware('auth:api')->post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::group(['middleware' => ['auth:api']], function () {
    // profile
    Route::get('/profile', [MemberController::class, 'profile'])->name('profile');
    Route::patch('/profile/update', [MemberController::class, 'updateProfile'])->name('update');


    // riwayat
    Route::get('/riwayat', [TransactionController::class, 'history'])->name('riwayat');
    Route::get('/riwayat/detail', [TransactionController::class, 'detail'])->name('riwayat.detail');

    // simpanan
    Route::get('/simpanan', [DepositController::class, 'getDeposit'])->name('simpanan');


    // pinjaman
    Route::get('/pinjaman', [LoanController::class, 'loans'])->name('pinjaman');
    Route::post('/pinjaman/pengajuan', [LoanController::class, 'pengajuanPinjaman'])->name('pinjaman.pengajuan');

    // slip pembayaran
});

Route::get('/unduh/slip', [SlipController::class, 'generate']);

// kolektor
Route::group(['prefix' => 'kolektor', 'middleware' => ['auth:api', 'checkRole:2']], function () {
    Route::get('/index', [CollectorController::class, 'index'])->name('kolektor');
    Route::get('/anggota-binaan', [CollectorController::class, 'getMember'])->name('anggota-binaan');
    Route::get('/detail-anggota/{id}', [CollectorController::class, 'detailMember'])->name('detail-anggota');

    Route::post('/tambah-simpanan/{id}', [DepositController::class, 'saveDeposit'])->name('tambah-simpanan');
    Route::get('/anggota-pinjaman', [CollectorController::class, 'memberLoan'])->name('pinjaman');
    Route::get('/info-pembayaran/{id_loan}', [LoanController::class, 'loanPaymentInfo'])->name('info-pembayaran');
    Route::post('/tambah-pinjaman', [LoanController::class, 'loanPayment'])->name('bayar_pinjaman');
    Route::get('/info-pinjaman/{id_loan}', [LoanController::class, 'loanInfo'])->name('info-pinjaman');

    Route::get('/loan-installments', [InstallmentController::class, 'loanInstallments'])->name('loan-installments');
    Route::post('/setoran/{id_loan}', [InstallmentController::class, 'setoran'])->name('setoran');

    // kunjungan hari ini
    Route::get('/kunjungan-hari-ini', [CollectorController::class, 'kunjunganHariIni'])->name('kunjungan-hari-ini');
});

// notifikasi
Route::group(['prefix' => 'notifikasi', 'middleware' => 'auth:api'], function () {
    Route::get('/get', [NotificationController::class, 'getByUser'])->name('get');
    Route::post('/read/{id}', [NotificationController::class, 'read'])->name('read');
});



