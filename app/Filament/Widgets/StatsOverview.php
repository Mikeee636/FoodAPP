<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Menghitung total pendapatan dari pesanan yang statusnya 'selesai' HARI INI.
        $revenueToday = Order::where('status', 'selesai')
            ->whereDate('updated_at', Carbon::today())
            ->sum('total_price');

        // Menghitung jumlah semua item dari pesanan yang statusnya 'selesai' HARI INI.
        $itemsSoldToday = OrderItem::whereHas('order', function ($query) {
            $query->where('status', 'selesai')->whereDate('updated_at', Carbon::today());
        })->sum('quantity');

        // Menghitung jumlah pesanan baru yang masuk HARI INI.
        $newOrdersToday = Order::whereDate('created_at', Carbon::today())->count();

        return [
            Stat::make('Pendapatan Hari Ini', 'Rp ' . number_format($revenueToday, 0, ',', '.'))
                ->description('Total dari pesanan selesai')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Item Terjual Hari Ini', $itemsSoldToday)
                ->description('Jumlah item dari pesanan selesai')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info'),
            Stat::make('Pesanan Baru Hari Ini', $newOrdersToday)
                ->description('Total pesanan yang masuk')
                ->descriptionIcon('heroicon-m-inbox')
                ->color('warning'),
        ];
    }
}
