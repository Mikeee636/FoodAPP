<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SalesChart extends ChartWidget
{
    protected static ?string $heading = 'Total Menu Terjual (7 Hari Terakhir)';
    protected static ?int $sort = 2; // Urutan widget di dashboard, setelah StatsOverview

    protected function getType(): string
    {
        return 'line'; // Jenis grafik: 'line' atau 'bar'
    }

    protected function getData(): array
    {
        // Ambil data dari database, gabungkan makanan dan minuman
        $data = OrderItem::query()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'selesai')
            ->whereBetween('orders.updated_at', [Carbon::now()->subDays(6)->startOfDay(), Carbon::now()->endOfDay()])
            ->select(
                DB::raw('DATE(orders.updated_at) as date'),
                DB::raw('SUM(order_items.quantity) as total_quantity')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy('date');

        // Siapkan array untuk label (hari) dan data penjualan
        $labels = [];
        $salesData = [];

        // Looping untuk 7 hari terakhir, dari 6 hari yang lalu sampai hari ini
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateString = $date->toDateString();

            // Tambahkan nama hari ke label (e.g., "Senin", "Selasa")
            $labels[] = $date->translatedFormat('l');

            // Cari total penjualan untuk hari ini, jika tidak ada, isi dengan 0
            $salesData[] = $data->get($dateString)->total_quantity ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Menu Terjual (porsi)',
                    'data' => $salesData,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
