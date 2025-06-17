<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?int $navigationSort = 2; // Atur urutan di sidebar

    public static function canCreate(): bool
    {
        // Menonaktifkan tombol "Create" atau "New Order"
        return false;
    }

    public static function form(Form $form): Form
    {
        // Form ini tidak lagi digunakan karena kita menonaktifkan pembuatan pesanan
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('Order ID')->searchable(),
                Tables\Columns\TextColumn::make('customer_name')->label('Nama Pelanggan')->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'baru' => 'gray',
                        'diproses' => 'warning',
                        'selesai' => 'success',
                        'dibatalkan' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('total_price')->label('Total Harga')->money('IDR'),
                Tables\Columns\TextColumn::make('created_at')->label('Waktu Pesanan')->dateTime(),
            ])
            ->defaultSort('created_at', 'desc') // Tampilkan pesanan terbaru di atas
            ->actions([
                Tables\Actions\ViewAction::make(),
                Action::make('terima_pesanan')
                    ->label('Terima')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn (Order $record) => $record->update(['status' => 'diproses']))
                    ->visible(fn (Order $record) => $record->status === 'baru'),
                Action::make('tandai_selesai')
                    ->label('Selesai')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->action(fn (Order $record) => $record->update(['status' => 'selesai']))
                    ->visible(fn (Order $record) => $record->status === 'diproses'),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }
}
