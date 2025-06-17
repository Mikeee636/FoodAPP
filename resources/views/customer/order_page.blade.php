<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Makanan - Resto Anda</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .add-button { border: 1px solid #d1d5db; color: #1d4ed8; font-weight: 600; padding: 6px 16px; border-radius: 9999px; transition: all 0.2s; background-color: white; }
        .add-button:hover { background-color: #eef2ff; }
        .add-button:disabled { background-color: #f3f4f6; color: #9ca3af; cursor: not-allowed; }
        .quantity-controls { display: flex; align-items: center; border: 1px solid #d1d5db; border-radius: 9999px; background-color: white; }
        .quantity-btn { color: #1d4ed8; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; font-size: 20px; line-height: 1; cursor: pointer; }
    </style>
</head>
<body class="bg-gray-100">

    <div class="container mx-auto max-w-6xl p-4 lg:p-8">
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Restoran Anda</h1>
            <p class="text-gray-600">Silakan pilih menu favoritmu di bawah ini.</p>
        </header>

        <!-- Container untuk Notifikasi -->
        <div id="notification-container">
            @if(session('success'))
                <div class="bg-green-100 border border-green-200 text-green-800 p-4 mb-6 rounded-lg" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-200 text-red-800 p-4 mb-6 rounded-lg" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border border-red-200 text-red-800 p-4 mb-6 rounded-lg" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <main class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <!-- Kolom Menu Makanan -->
            <section class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm">
                <h2 class="text-2xl font-bold mb-6">Daftar Menu</h2>
                <div class="space-y-5">
                    @forelse($menus as $menu)
                        <article class="flex items-center">
                            <img src="{{ $menu->image_url ?? 'https://placehold.co/100x100/e2e8f0/64748b?text=Menu' }}" alt="{{ $menu->name }}" class="w-24 h-24 object-cover rounded-md flex-shrink-0">
                            <div class="ml-4 flex-grow">
                                <h3 class="font-semibold text-lg text-gray-800">{{ $menu->name }}</h3>
                                <p class="text-gray-800 font-bold mt-1">Rp{{ number_format($menu->price, 0, ',', '.') }}</p>
                                <p class="text-sm text-gray-500 mt-2">Stok Tersisa: <span class="font-medium text-blue-600">{{ $menu->stock }}</span></p>
                            </div>
                            <div id="menu-controls-{{ $menu->id }}" class="ml-4 flex-shrink-0" data-stock="{{ $menu->stock }}" data-name="{{ $menu->name }}" data-price="{{ $menu->price }}">
                                <!-- Tombol akan di-render oleh JavaScript -->
                            </div>
                        </article>
                    @empty
                        <p class="text-center text-gray-500 py-10">Saat ini belum ada menu yang tersedia.</p>
                    @endforelse
                </div>
            </section>

            <!-- Kolom Keranjang & Form -->
            <aside class="lg:col-span-1">
                <div class="bg-white p-6 rounded-xl shadow-sm sticky top-8">
                    <h2 class="text-2xl font-bold mb-4">Detail Pesanan</h2>
                    <div id="cart-items" class="mb-4 space-y-4 max-h-60 overflow-y-auto pr-2">
                        <p class="text-gray-500 text-center py-4">Keranjang masih kosong</p>
                    </div>
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <div class="flex justify-between items-center font-bold text-xl">
                            <span>Total Bayar</span>
                            <span id="cart-total">Rp 0</span>
                        </div>
                    </div>

                    <form id="order-form" action="{{ route('customer.place_order') }}" method="POST" class="mt-8 space-y-4">
                        @csrf
                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                            <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700">Alamat Pengiriman</label>
                            <textarea id="address" name="address" rows="3" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>{{ old('address') }}</textarea>
                        </div>
                        <input type="hidden" name="items" id="items-input">
                        <button type="submit" id="submit-order-btn" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg text-lg transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed">
                            Konfirmasi Pesanan
                        </button>
                    </form>
                </div>
            </aside>
        </main>
    </div>

    <script>
        // State management
        let cart = {};

        // Functions
        function renderMenuControls(menuId) {
            const container = document.getElementById(`menu-controls-${menuId}`);
            if (!container) return;

            const stock = parseInt(container.dataset.stock);
            const itemInCart = cart[menuId];

            if (stock <= 0) {
                container.innerHTML = `<button class="add-button" disabled>Habis</button>`;
                return;
            }

            if (itemInCart) {
                container.innerHTML = `
                    <div class="quantity-controls">
                        <button class="quantity-btn" onclick="updateQuantity(${menuId}, -1)">-</button>
                        <span class="mx-3 font-semibold text-sm">${itemInCart.quantity}</span>
                        <button class="quantity-btn" onclick="updateQuantity(${menuId}, 1)">+</button>
                    </div>
                `;
            } else {
                container.innerHTML = `<button class="add-button" onclick="addToCart(${menuId})">Tambah</button>`;
            }
        }

        function addToCart(id) {
            const controls = document.getElementById(`menu-controls-${id}`);
            const stock = parseInt(controls.dataset.stock);

            if (stock > 0) {
                const name = controls.dataset.name;
                const price = parseFloat(controls.dataset.price);
                cart[id] = { name, price, quantity: 1, stock };
                updateAll();
            }
        }

        function updateQuantity(id, change) {
            if (cart[id]) {
                const newQty = cart[id].quantity + change;
                if (newQty > 0 && newQty <= cart[id].stock) {
                    cart[id].quantity = newQty;
                } else if (newQty <= 0) {
                    delete cart[id];
                } else {
                    alert(`Stok untuk menu ini hanya tersisa ${cart[id].stock}.`);
                }
                updateAll();
            }
        }

        function updateCartView() {
            const cartItemsContainer = document.getElementById('cart-items');
            const cartTotalElement = document.getElementById('cart-total');
            const itemsInput = document.getElementById('items-input');
            const submitBtn = document.getElementById('submit-order-btn');
            let total = 0;

            cartItemsContainer.innerHTML = '';

            if (Object.keys(cart).length === 0) {
                cartItemsContainer.innerHTML = '<p class="text-gray-500 text-center py-4">Keranjang masih kosong</p>';
                submitBtn.disabled = true;
            } else {
                submitBtn.disabled = false;
                for (const id in cart) {
                    const item = cart[id];
                    total += item.price * item.quantity;
                    const itemElement = document.createElement('div');
                    itemElement.innerHTML = `
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-sm">${item.name}</p>
                                <p class="text-xs text-gray-500">Rp${item.price.toLocaleString('id-ID')} x ${item.quantity}</p>
                            </div>
                            <span class="font-semibold text-sm">Rp${(item.price * item.quantity).toLocaleString('id-ID')}</span>
                        </div>
                    `;
                    cartItemsContainer.appendChild(itemElement);
                }
            }

            cartTotalElement.innerText = `Rp ${total.toLocaleString('id-ID')}`;
            itemsInput.value = JSON.stringify(Object.keys(cart).length > 0 ? cart : {});
        }

        function updateAll() {
            updateCartView();
            document.querySelectorAll('[id^="menu-controls-"]').forEach(container => {
                const menuId = container.id.split('-')[2];
                renderMenuControls(menuId);
            });
        }

        // Initial Render on page load
        document.addEventListener('DOMContentLoaded', () => {
            updateAll();
        });
    </script>
</body>
</html>
