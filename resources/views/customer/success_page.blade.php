<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil - Resto Anda</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">

    <div class="container mx-auto max-w-2xl p-4 lg:p-8 mt-10">
        <div class="bg-white p-8 rounded-xl shadow-lg text-center">

            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-6">
                <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <h1 class="text-3xl font-bold text-gray-900">Pesanan Anda Diterima!</h1>
            <p class="text-gray-600 mt-2">Terima kasih telah melakukan pemesanan di restoran kami.</p>

            <div class="border-t border-b border-gray-200 my-8 py-6">
                <h2 class="text-lg font-semibold text-gray-800">Ringkasan Pesanan</h2>
                <div class="text-left max-w-sm mx-auto mt-4 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Nomor Pesanan:</span>
                        <span class="font-bold text-gray-800">#{{ $order->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Nama Pemesan:</span>
                        <span class="font-bold text-gray-800">{{ $order->customer_name }}</span>
                    </div>
                     <div class="flex justify-between">
                        <span class="text-gray-500">Metode Pembayaran:</span>
                        <span class="font-bold text-gray-800">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                    </div>
                    <div class="flex justify-between items-baseline">
                        <span class="text-gray-500">Total Pembayaran:</span>
                        <span class="font-bold text-blue-600 text-2xl">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- =============================================================== --}}
            {{--   BLOK KONDISIONAL UNTUK METODE PEMBAYARAN TRANSFER BANK         --}}
            {{-- =============================================================== --}}
            @if ($order->payment_method == 'transfer_bank')
            <div class="bg-orange-50 border border-orange-200 text-orange-800 p-6 rounded-lg text-left mb-8">
                <h3 class="font-bold text-lg mb-4 text-center">Petunjuk Pembayaran</h3>
                <p class="text-center mb-4 text-sm">Silakan lakukan transfer sejumlah total pembayaran ke rekening berikut:</p>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="font-medium">Bank:</span>
                        <span>{{ config('payment.bank_transfer.name') }}</span>
                    </div>
                     <div class="flex justify-between">
                        <span class="font-medium">Nomor Rekening:</span>
                        <span class="font-mono font-bold tracking-wider">{{ config('payment.bank_transfer.number') }}</span>
                    </div>
                     <div class="flex justify-between">
                        <span class="font-medium">Atas Nama:</span>
                        <span>{{ config('payment.bank_transfer.holder') }}</span>
                    </div>
                </div>
                <p class="text-center mt-6 text-xs text-gray-600">Mohon selesaikan pembayaran dalam 1x24 jam. Pesanan Anda akan kami proses setelah pembayaran terkonfirmasi.</p>
            </div>
            @endif
            {{-- =============================================================== --}}
            {{--   AKHIR BLOK KONDISIONAL                                        --}}
            {{-- =============================================================== --}}


            <p class="text-sm text-gray-500">
                @if ($order->payment_method == 'cash')
                    Silakan siapkan uang pas saat kurir kami tiba.
                @else
                    Kami akan segera memproses pesanan Anda setelah pembayaran selesai.
                @endif
            </p>

            <a href="{{ route('customer.order_page') }}" class="mt-8 inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition-colors">
                Pesan Lagi
            </a>
        </div>
    </div>

</body>
</html>
