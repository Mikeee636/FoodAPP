<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerPageController;

Route::get('/', [CustomerPageController::class, 'index'])->name('customer.order_page');
Route::post('/place-order', [CustomerPageController::class, 'placeOrder'])->name('customer.place_order');
