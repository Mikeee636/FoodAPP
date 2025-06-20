<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuResource\Pages;
use App\Models\Menu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
// Import class yang dibutuhkan
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\OrderResource\RelationManagers\ItemsRelationManager;


class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Menu')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\Select::make('type')
                    ->label('Jenis Menu')
                    ->options([
                        'makanan' => 'Makanan',
                        'minuman' => 'Minuman',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('price')
                    ->label('Harga')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),

                // --- FITUR BARU DITAMBAHKAN DI SINI ---
                Toggle::make('is_available')
                    ->label('Tersedia untuk Dijual')
                    ->default(true)
                    ->helperText('Jika nonaktif, menu tidak akan muncul di halaman pelanggan.'),
                Forms\Components\TextInput::make('stock')
                    ->label('Jumlah Stok')
                    ->numeric()
                    ->required()

                    ->helperText('Stok akan berkurang otomatis setiap ada pesanan.'),
                // --- AKHIR FITUR BARU ---

                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->columnSpanFull(),
               FileUpload::make('image_url') // Nama kolom database Anda
                    ->label('Gambar Menu')
                    ->directory('menu-images') // Direktori di storage/app/public untuk menyimpan gambar
                    ->image() // Hanya izinkan upload gambar

                    ->columnSpanFull()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Menu')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis')
                    ->badge(),

                // --- TAMPILAN BARU DI TABEL ---
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stok')
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state === 0 => 'danger',
                        $state < 10 => 'warning',
                        default => 'success',
                    }),
                IconColumn::make('is_available')
                    ->label('Tersedia')
                    ->boolean(),
                // --- AKHIR TAMPILAN BARU ---

                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}
