<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\ItemsRelationManager;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        // Mendefinisikan field-field yang akan muncul di form Edit
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pelanggan')
                    ->schema([
                        Forms\Components\TextInput::make('customer_name')
                            ->label('Nama Pelanggan')
                            ->required(),
                        Forms\Components\TextInput::make('phone_number')
                            ->label('Nomor Telepon')
                            ->tel(),
                        Forms\Components\Textarea::make('address')
                            ->label('Alamat')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Detail Pesanan')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'baru' => 'Baru',
                                'diproses' => 'Diproses',
                                'selesai' => 'Selesai',
                                'dibatalkan' => 'Dibatalkan',
                            ])
                            ->required(),
                        Forms\Components\Select::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->options([
                                'cash' => 'Cash',
                                'transfer_bank' => 'Transfer Bank',
                            ]),
                        Forms\Components\TextInput::make('total_price')
    ->label('Total Harga')
    ->prefix('Rp') // <-- Ganti ->money() dengan ->prefix()
    ->numeric()
    ->readOnly(), // Total harga tidak boleh diedit manual
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('Order ID')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('customer_name')->label('Nama Pelanggan')->searchable(),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'baru' => 'gray', 'diproses' => 'warning', 'selesai' => 'success', 'dibatalkan' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('payment_method')->label('Pembayaran')
                    ->formatStateUsing(fn (?string $state): string => ucfirst(str_replace('_', ' ', $state ?? '')))
                    ->icon(fn (?string $state): string => match ($state) {
                        'cash' => 'heroicon-o-banknotes', 'transfer_bank' => 'heroicon-o-credit-card', default => 'heroicon-o-question-mark-circle',
                    }),
                Tables\Columns\TextColumn::make('total_price')->label('Total Harga')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Waktu Pesanan')->dateTime()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(), // <-- TOMBOL EDIT SEKARANG DITAMBAHKAN
                Action::make('terima_pesanan')
                    ->label('Terima')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Terima Pesanan')
                    ->modalDescription('Anda yakin ingin menerima dan mulai memproses pesanan ini?')
                    ->action(function (Order $record) {
                        $record->update(['status' => 'diproses']);
                        Notification::make()->title('Pesanan diterima')->body('Pesanan sekarang sedang diproses.')->success()->send();
                    })
                    ->visible(fn (Order $record) => $record->status === 'baru'),
                    Action::make('tandai_selesai')
                    ->label('Selesai')
                    ->icon('heroicon-o-check-badge')
                    ->color('primary') // Menggunakan warna biru agar beda dengan 'Terima'
                    ->requiresConfirmation()
                    ->modalHeading('Tandai Pesanan Selesai')
                    ->modalDescription('Anda yakin pesanan ini sudah selesai dan diterima oleh pelanggan?')
                    ->action(function (Order $record) {
                        $record->update(['status' => 'selesai']);
                        Notification::make()->title('Pesanan Selesai')->body('Pesanan telah ditandai sebagai selesai.')->success()->send();
                    })
                    ->visible(fn (Order $record) => $record->status === 'diproses'),

                Action::make('batalkan_pesanan')
                    // ... (kode Batalkan Pesanan Anda sudah benar)
                    ->label('Batalkan')->icon('heroicon-o-x-circle')->color('danger')->requiresConfirmation()
                    ->action(function (Order $record) {
                        // ... logika pembatalan ...
                    })
                    ->visible(fn (Order $record) => in_array($record->status, ['baru', 'diproses'])),
            ]);
    }

    public static function getRelations(): array
    {
        // Mengaktifkan kembali Relation Manager untuk Item
        return [
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            // Daftarkan halaman Edit dan View
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'), // <-- TAMBAHKAN HALAMAN EDIT
        ];
    }
}
