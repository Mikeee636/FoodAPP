<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class CustomerPageController extends Controller
{
    public function index()
    {
        $menus = Menu::where('is_available', true)->orderBy('name', 'asc')->get();
        return view('customer.order_page', compact('menus'));
    }

    public function placeOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:1000',
            'items' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $cartItems = json_decode($request->items, true);

        if (empty($cartItems)) {
            return back()->with('error', 'Keranjang belanja Anda kosong!');
        }

        try {
            DB::transaction(function () use ($cartItems, $request) {
                $totalPrice = 0;
                $menuIds = array_keys($cartItems);
                $menusInStock = Menu::whereIn('id', $menuIds)->where('is_available', true)->lockForUpdate()->get()->keyBy('id');

                // Validasi stok
                foreach ($cartItems as $menuId => $item) {
                    if (!isset($menusInStock[$menuId]) || $menusInStock[$menuId]->stock < $item['quantity']) {
                        $menuName = $menusInStock[$menuId]->name ?? 'Menu tidak dikenal';
                        throw new Exception("Stok untuk menu '{$menuName}' tidak mencukupi.");
                    }
                }

                // Hitung total dan siapkan data item
                $orderItemsData = [];
                foreach ($cartItems as $menuId => $item) {
                    $menu = $menusInStock[$menuId];
                    $totalPrice += $menu->price * $item['quantity'];
                    $orderItemsData[] = ['menu_id' => $menuId, 'quantity' => $item['quantity'], 'price' => $menu->price];
                }

                // Buat pesanan
                $order = Order::create([
                    'customer_name' => $request->customer_name,
                    'phone_number' => $request->phone_number,
                    'address' => $request->address,
                    'total_price' => $totalPrice,
                    'status' => 'baru',
                ]);

                // Simpan item pesanan dan kurangi stok
                $order->items()->createMany($orderItemsData);
                foreach ($cartItems as $menuId => $item) {
                    $menusInStock[$menuId]->decrement('stock', $item['quantity']);
                }
            });
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('customer.order_page')->with('success', 'Pesanan Anda telah diterima!');
    }
}
