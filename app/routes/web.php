<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController; 
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\XrplController;
use App\Http\Controllers\Auth\LoginController;

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('login/google', 'App\Http\Controllers\Auth\LoginController@redirectToGoogle');
Route::get('login/google/callback', 'App\Http\Controllers\Auth\LoginController@handleGoogleCallback');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

#Route::get('xrpl/wallet', 'App\Http\Controllers\WalletController@wallet');
Route::get('/xrpl', [XrplController::class, 'create'])->name('xrpl.create');
Route::get('/xrpl/info', [XrplController::class, 'account_info'])->name('xrpl.account_info');
Route::get('/xrpl/set', [XrplController::class, 'account_set'])->name('xrpl.account_set');



Route::prefix('payment')->name('payment.')->group(function () {
    Route::get('/create', 'App\Http\Controllers\PaymentController@create')->name('create');
    Route::post('/store', 'App\Http\Controllers\PaymentController@store')->name('store');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ログインしていないと不可にする
Route::middleware('auth')->group(function(){
    // ログイン後の画面
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

#Route::prefix('payment')->name('payment.')->group(function () {
#    Route::get('/create', [PaymentController::class, 'create'])->name('create');
#    Route::post('/store', [PaymentController::class, 'store'])->name('store');
#});

});

require __DIR__.'/auth.php';
