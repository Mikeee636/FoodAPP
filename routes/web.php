<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerPageController;
use App\Http\Controllers\OrderSuccessController; // <-- BARIS INI DITAMBAHKAN

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

// Route untuk menampilkan halaman pemesanan utama
Route::get('/', [CustomerPageController::class, 'index'])->name('customer.order_page');

// Route untuk memproses form pemesanan
Route::post('/place-order', [CustomerPageController::class, 'placeOrder'])->name('customer.place_order');

// Route untuk menampilkan halaman "Pesanan Berhasil" setelah submit
Route::get('/order/success/{order}', [OrderSuccessController::class, 'index'])->name('order.success'); // <-- BARIS INI DITAMBAHKAN
