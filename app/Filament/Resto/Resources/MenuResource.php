<?php

namespace App\Filament\Resto\Resources;

use App\Filament\Resto\Resources\MenuResource\Pages;
use App\Filament\Resto\Resources\MenuResource\RelationManagers;
use App\Models\Menu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // restaurant_id dihapus dari sini karena akan diisi otomatis
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
                FileUpload::make('image')
                    ->image()
                    ->directory('menu-images'), // Menyimpan gambar di storage/app/public/menu-images
                Toggle::make('is_available')
                    ->required()
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // restaurant_id juga dihapus dari tabel karena tidak relevan bagi pemilik
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_available')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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

    /**
     * Memastikan data yang ditampilkan hanya milik restoran dari user yang sedang login.
     */
    public static function getEloquentQuery(): Builder
    {
        // Ambil ID restoran milik user yang sedang login
        $restaurantId = auth()->user()->restaurant->id;

        // Tampilkan hanya menu yang restaurant_id-nya sama dengan ID restoran milik user
        return parent::getEloquentQuery()->where('restaurant_id', $restaurantId);
    }

    /**
     * Menambahkan data restaurant_id secara otomatis sebelum data menu dibuat.
     */
    protected static function mutateFormDataBeforeCreate(array $data): array
    {
        // Ambil ID restoran milik user yang sedang login dan tambahkan ke data
        $data['restaurant_id'] = auth()->user()->restaurant->id;

        return $data;
    }
}
