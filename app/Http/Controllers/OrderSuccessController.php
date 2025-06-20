<?php

namespace App\Http\Controllers;

use App\Models\Order; // Impor model Order
use Illuminate\Http\Request;

class OrderSuccessController extends Controller
{
    /**
     * Menampilkan halaman konfirmasi pesanan berhasil.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\View\View
     */
    public function index(Order $order)
    {
        // Laravel secara otomatis akan mencari Order berdasarkan ID dari URL ({order})
        // Ini disebut Route Model Binding.

        // Kirim data pesanan ke view
        return view('customer.success_page', compact('order'));
    }
}
