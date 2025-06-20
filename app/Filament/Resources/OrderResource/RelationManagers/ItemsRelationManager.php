<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Menu;
use App\Models\Order;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    protected static ?string $recordTitleAttribute = 'menu_id';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('menu_id')
                    ->label('Menu')
                    ->options(Menu::where('is_available', true)->pluck('name', 'id'))
                    ->searchable()->required()->reactive()
                    ->disabled(fn ($context) => $context === 'edit'),
                TextInput::make('quantity')
                    ->label('Kuantitas')
                    ->numeric()->minValue(1)->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('menu.name')->label('Nama Menu'),
                Tables\Columns\TextColumn::make('quantity')->label('Kuantitas'),
                Tables\Columns\TextColumn::make('price')->label('Harga Satuan')->money('IDR'),
                Tables\Columns\TextColumn::make('subtotal')->label('Subtotal')->money('IDR')
                    ->state(fn (Model $record): float => $record->price * $record->quantity),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $menu = Menu::find($data['menu_id']);
                        if ($menu) { $data['price'] = $menu->price; }
                        return $data;
                    })
                    ->after(function (Model $record) {
                        $menu = Menu::find($record->menu_id);
                        if ($menu->stock < $record->quantity) {
                            Notification::make()->title('Stok tidak mencukupi!')->danger()->send();
                            $record->delete();
                            return;
                        }
                        $menu->decrement('stock', $record->quantity);
                        $this->updateOrderTotalPrice();
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->action(function (Model $record, array $data): void {
                        $oldQuantity = $record->quantity;
                        $newQuantity = $data['quantity'];
                        $quantityDiff = $newQuantity - $oldQuantity;
                        $menu = $record->menu;
                        if ($quantityDiff > 0 && $menu->stock < $quantityDiff) {
                            Notification::make()->title('Stok tidak mencukupi!')->body("Hanya tersisa {$menu->stock} stok.")->danger()->send();
                            return;
                        }
                        $record->update(['quantity' => $newQuantity]);
                        if ($quantityDiff > 0) {
                            $menu->decrement('stock', $quantityDiff);
                        } elseif ($quantityDiff < 0) {
                            $menu->increment('stock', abs($quantityDiff));
                        }
                        if ($quantityDiff !== 0) {
                            $this->updateOrderTotalPrice();
                        }
                    }),
                Tables\Actions\DeleteAction::make()
                    ->after(function (Model $record) {
                        $menu = $record->menu;
                        if ($menu) { $menu->increment('stock', $record->quantity); }
                        $this->updateOrderTotalPrice();
                    }),
            ]);
    }

    protected function updateOrderTotalPrice(): void
    {
        $order = $this->getOwnerRecord();
        if ($order) {
            $total = $order->items()->sum(DB::raw('price * quantity'));
            $order->update(['total_price' => $total]);
        }
    }
}
