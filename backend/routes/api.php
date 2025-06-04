<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\DepositController;
use App\Http\Controllers\Api\LoanController;
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

Route::post('/login', [LoginController::class, 'login'])
    ->name('login');
Route::post('/register', [LoginController::class, 'register']);
Route::middleware('auth:api')->post('/logout', [LoginController::class, 'logout']);

Route::middleware( ['auth:api','id_role:3'])
    ->get('/user', function (Request $request) {
    return response()->json(['msg' => 'Selamat datang di dashboard anggota']);
});

Route::middleware('auth:api')
    ->put('profile/update', [MemberController::class, 'updateProfile'])
    ->name('profile.update');


Route::middleware('auth:api')->get('/check-auth', function () {
    return response()->json([
        'success' => true,
        'user' => auth()->user()
    ]);
});

// simpanan
Route::middleware('auth:api')
    ->get('/simpanan', [DepositController::class, 'getDeposit']);
Route::middleware('auth:api')
    ->post('/simpanan', [DepositController::class, 'saveDeposit']);

// pinjaman
Route::middleware('auth:api')
    ->post('/pinjaman/pengajuan', [LoanController::class, 'pengajuanPinjaman']);


