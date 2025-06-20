<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order; // Pastikan model Order diimpor
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Log; // Opsional: Untuk logging error

class CustomerPageController extends Controller
{
    public function index()
    {
        $menus = Menu::where('is_available', true)->orderBy('name', 'asc')->get();

        // Pisahkan menu berdasarkan kategori
        // Asumsi 'is_parent' dan 'parent_id' tidak digunakan untuk fitur ini
        // Jika digunakan, sesuaikan query ini untuk hanya menampilkan menu yang bisa dipilih langsung
        $makanan = $menus->where('type', 'makanan');
        $minuman = $menus->where('type', 'minuman');

        return view('customer.order_page', compact('menus','makanan', 'minuman'));
    }

    public function placeOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:1000',
            'items' => 'required|string', // String JSON dari JS
            'payment_method' => 'required|in:cash,transfer_bank', // VALIDASI BARU UNTUK METODE PEMBAYARAN
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $cartItems = json_decode($request->items, true);

        if (empty($cartItems)) {
            return back()->with('error', 'Keranjang belanja Anda kosong!');
        }

        $createdOrder = null; // Inisialisasi untuk menyimpan objek Order yang dibuat setelah transaksi

        try {
            DB::transaction(function () use ($cartItems, $request, &$createdOrder) { // '&createdOrder' untuk passing by reference
                $totalPrice = 0;
                $menuIds = array_keys($cartItems);
                // Lock for update untuk mencegah race condition pada stok
                $menusInStock = Menu::whereIn('id', $menuIds)->where('is_available', true)->lockForUpdate()->get()->keyBy('id');

                // Validasi stok
                foreach ($cartItems as $menuId => $item) {
                    if (!isset($menusInStock[$menuId]) || $menusInStock[$menuId]->stock < $item['quantity']) {
                        $menuName = $menusInStock[$menuId]->name ?? 'Menu tidak dikenal';
                        throw new Exception("Stok untuk menu '{$menuName}' tidak mencukupi.");
                    }
                }

                // Hitung total harga dan siapkan data item pesanan
                $orderItemsData = [];
                foreach ($cartItems as $menuId => $item) {
                    $menu = $menusInStock[$menuId];
                    $totalPrice += $menu->price * $item['quantity'];
                    $orderItemsData[] = ['menu_id' => $menuId, 'quantity' => $item['quantity'], 'price' => $menu->price];
                }

                // Buat pesanan utama
                $createdOrder = Order::create([
                    'customer_name' => $request->customer_name,
                    'phone_number' => $request->phone_number,
                    'address' => $request->address,
                    'total_price' => $totalPrice,
                    'status' => 'baru',
                    'payment_method' => $request->payment_method, // SIMPAN METODE PEMBAYARAN DI SINI
                ]);

                // Simpan detail item pesanan dan kurangi stok
                $createdOrder->items()->createMany($orderItemsData);
                foreach ($cartItems as $menuId => $item) {
                    $menusInStock[$menuId]->decrement('stock', $item['quantity']);
                }
            });

            // Setelah transaksi berhasil di-commit, lakukan redirect
            if ($createdOrder) {
                // Mengarahkan ke route 'order.success' dengan ID pesanan yang baru dibuat
                // Pastikan route 'order.success' sudah terdefinisi di routes/web.php
                return redirect()->route('order.success', ['order' => $createdOrder->id]);
            } else {
                // Ini adalah fallback jika somehow $createdOrder tidak terisi, meskipun seharusnya tidak terjadi
                return back()->with('error', 'Pesanan berhasil diproses, namun terjadi kesalahan dalam pengalihan ke halaman konfirmasi.')->withInput();
            }

        } catch (Exception $e) {
            // Error dari validasi stok atau proses database lainnya akan ditangkap di sini
            // Transaksi otomatis akan di-rollback jika ada exception.
            Log::error("Gagal menempatkan pesanan: " . $e->getMessage() . " di " . $e->getFile() . " baris " . $e->getLine());
            return back()->with('error', $e->getMessage())->withInput(); // Mengembalikan pesan error ke tampilan
        }
    }
}
