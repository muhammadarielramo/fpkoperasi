<?php

use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\DashboardController;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Web\CashFlowController;
use App\Http\Controllers\uploadController;
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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'destroy'])->name('admin.logout');

// reset password
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->name('show-reset-password');
Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('reset-password');

Route::middleware(['admin'])->group(function () {
    // dashboard
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function(){
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::get('/anggota', [MemberController::class, 'getDatas'])->name('data-anggota');
        Route::get('/kolektor', [CollectorController::class, 'getDatas'])->name('data-kolektor');
        Route::get('/pendaftaran-anggota', [RegisterController::class, 'show'])->name('pendaftaran-anggota');
        Route::get('/pendaftaran-anggota/{status}', [RegisterController::class, 'show'])->name('pendaftaran.status');
        Route::get('/data', AdminController::class)->name('data-admin');
    });

    // anggota
    Route::group(['prefix' => 'anggota', 'as' => 'anggota.'], function(){
        Route::get('/info/{id}', [MemberController::class, 'detailAnggota'])->name('info');
        Route::delete('/hapus/{id}', [MemberController::class, 'destroy'])->name('hapus');
        Route::get('/edit/{id}', [MemberController::class, 'edit'])->name('edit');
        Route::put('/edit/{id}', [MemberController::class, 'update'])->name('update');
        Route::get('/{id}/tambah-kolektor', [MemberController::class, 'showAddMember'])->name('tambah-kolektor');
        Route::post('/{id}/tambah-kolektor', [MemberController::class, 'saveAddMember'])->name('simpan-kolektor');
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
        Route::put('/pengajuan/update/{id}', [LoanController::class, 'responPengajuan'])->name('updateStatus');
        Route::get('/detail/{id}', [LoanController::class, 'detailPinjaman'])->name('detail');
        Route::patch('/pinjaman/{id}/lunas', [LoanController::class, 'lunas'])->name('lunas');
    });

    // simpanan
    Route::group(['prefix' => 'simpanan', 'as' => 'simpanan.'], function(){
        Route::get('/data', [DepositController::class, 'index'])->name('index');
        Route::get('/history',[TransactionController::class, 'deposit'])->name('history');
        Route::get('/history/info/{id}', [TransactionController::class, 'infoHistori'])->name('histori.info');
    });

    // pendaftaran
    Route::group(['prefix' => 'register', 'as' => 'register.',], function(){
        Route::delete('/tolak/{id}', [RegisterController::class, 'tolak'])->name('tolak');
        Route::post('/terima/{id}', [RegisterController::class, 'terima'])->name('terima');
        Route::post('/verifikasi/{id}', [RegisterController::class, 'verifikasi'])->name('verifikasi');
    });


});

<<<<<<< HEAD
// anggota
Route::group(['prefix' => 'anggota', 'as' => 'anggota.'], function(){
    Route::get('/info/{id}', [MemberController::class, 'detailAnggota'])->name('info');
    Route::delete('/hapus/{id}', [MemberController::class, 'destroy'])->name('hapus');
    Route::get('/edit/{id}', [MemberController::class, 'edit'])->name('edit');
    Route::put('/edit/{id}', [MemberController::class, 'update'])->name('update');
    Route::get('/{id}/tambah-kolektor', [MemberController::class, 'showAddMember'])->name('tambah-kolektor');
    Route::post('/{id}/tambah-kolektor', [MemberController::class, 'saveAddMember'])->name('simpan-kolektor');
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
    Route::put('/pengajuan/update/{id}', [LoanController::class, 'responPengajuan'])->name('updateStatus');
});

// simpanan
Route::group(['prefix' => 'simpanan', 'as' => 'simpanan.'], function(){
    Route::get('/data', [DepositController::class, 'index'])->name('index');
    Route::get('/history',[TransactionController::class, 'deposit'])->name('history');
    Route::get('/history/info/{id}', [TransactionController::class, 'infoHistori'])->name('histori.info');
});

Route::group(['prefix' => 'register', 'as' => 'register.'], function(){
    Route::delete('/tolak/{id}', [RegisterController::class, 'tolak'])->name('tolak');
    Route::post('/terima/{id}', [RegisterController::class, 'terima'])->name('terima');
    Route::post('/verifikasi/{id}', [RegisterController::class, 'verifikasi'])->name('verifikasi');
});


=======
>>>>>>> backend2
// verifikasi
Route::get('/verifikasi/{id}', [RegisterController::class, 'verifPage'])->name('verifikasi.halaman');
Route::post('/verifikasi/{id}', [RegisterController::class, 'verifikasi'])->name('verifikasi');

// history
<<<<<<< HEAD
Route::get('/history/daily', [TransactionController::class, 'dailyHistory'])->name('history.daily');
Route::get('/history/monthly', [TransactionController::class, 'monthlyHistory'])->name('history.monthly');
// export
Route::get('/transactions/export', [TransactionController::class, 'export'])->name('transactions.export');


=======
Route::get('/cashflowy/daily', [CashFlowController::class, 'ShowKas'])->name('history.daily');
Route::get('/cashflow/create', [CashFlowController::class, 'showCreate'])->name('history.create.show');
Route::get('/cashflow/export', [CashFlowController::class, 'export'])->name('cashflow.export');
Route::post('/cashflow/save', [CashFlowController::class, 'create'])->name('history.create');
>>>>>>> backend2

Route::get('/transactions/export', [TransactionController::class, 'export'])->name('transactions.export');
// Route::get('/pengeluaran-kas', [CashFlowController::class, 'ShowKas'])->name('history.saldo');
// Route::get('/pengeluaran-kas', [CashFlowController::class, 'create'])->name('cash-flows.create');

// relasi
Route::get('relasi/tambah/{id}', [RelationController::class, 'create'])->name('relasi.create');
Route::post('relasi/simpan', [RelationController::class, 'store'])->name('relasi.store');

Route::get('/simpanan/pengajuan', [LoanController::class, 'getPengajuan']);
Route::post('/simpanan/pengajuan/{id}', [LoanController::class, 'responPengajuan'])
    ->name('pinjaman.respon');

<<<<<<< HEAD
=======
// upload kt
Route::get('/upload-ktp', [uploadController::class, 'index'])->name('upload-ktp');
Route::post('/upload-ktp', [uploadController::class, 'upload'])->name('upload-ktp');

>>>>>>> backend2


