<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::view('/', 'auth.login')->name('home');

Route::get('/login', [Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [Auth\LoginController::class, 'login']);
Route::get('/register', [Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [Auth\RegisterController::class, 'register']);
Route::post('/logout', [Auth\LoginController::class, 'logout'])->name('logout');
Auth::routes();

// Route::get('/', function () {
//     return redirect()->route('login');
// });

// Auth Routes

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Produk (Hanya admin)
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    // Pemesanan & Kasir (Admin + Kasir)
    Route::resource('orders', OrderController::class)->only([
        'create',
        'store',
        'show',
        'destroy'
    ]);

    // Selain resource, tambahkan route untuk pay dan print:
    Route::post('orders/{order}/pay', [OrderController::class, 'pay'])->name('orders.pay');
    Route::get('orders/{order}/print', [OrderController::class, 'print'])->name('orders.print');

    // Riwayat Transaksi (Admin + Kasir)
    Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
});