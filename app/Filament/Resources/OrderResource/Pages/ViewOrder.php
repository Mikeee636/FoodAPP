<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Informasi Pesanan')
                    ->schema([
                        Components\TextEntry::make('customer_name')->label('Nama Pelanggan'),
                        Components\TextEntry::make('phone_number')->label('Nomor Telepon'),
                        Components\TextEntry::make('address')->label('Alamat')->columnSpanFull(),
                        Components\TextEntry::make('status')->badge(),
                        Components\TextEntry::make('total_price')->label('Total Harga')->money('IDR'),
                        Components\TextEntry::make('created_at')->label('Waktu Pesan')->dateTime(),
                    ])->columns(2),
                Components\Section::make('Item Pesanan')
                    ->schema([
                        Components\RepeatableEntry::make('items')
                            ->schema([
                                Components\TextEntry::make('menu.name')->label('Menu'),
                                Components\TextEntry::make('quantity')->label('Jumlah'),
                                Components\TextEntry::make('price')->label('Harga Satuan')->money('IDR'),
                            ])->columns(3)
                    ])
            ]);
    }
}
