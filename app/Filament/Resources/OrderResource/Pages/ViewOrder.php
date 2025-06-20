<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    /**
     * Mendefinisikan aksi yang muncul di header halaman (pojok kanan atas).
     * Secara default bisa berisi tombol Edit, tapi kita biarkan kosong untuk saat ini.
     */
    protected function getHeaderActions(): array
    {
        return [
            // Actions\EditAction::make(),
        ];
    }

    /**
     * Mendefinisikan struktur tampilan detail untuk sebuah record.
     */
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // Hanya menampilkan Section untuk Informasi Pesanan Utama
                Components\Section::make('Informasi Pesanan')
                    ->schema([
                        Components\TextEntry::make('customer_name')->label('Nama Pelanggan'),
                        Components\TextEntry::make('phone_number')->label('Nomor Telepon'),
                        Components\TextEntry::make('address')->label('Alamat')->columnSpanFull(),
                        Components\TextEntry::make('status')->badge(),
                        Components\TextEntry::make('total_price')->label('Total Harga')->money('IDR'),
                        Components\TextEntry::make('created_at')->label('Waktu Pesan')->dateTime(),
                    ])->columns(2),

                // Bagian 'Item Pesanan' yang menyebabkan duplikasi dan konflik sudah dihapus dari sini.
                // Tampilan item sekarang akan sepenuhnya ditangani oleh ItemsRelationManager.
            ]);
    }
}
