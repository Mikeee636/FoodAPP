<?php

namespace App\Filament\Resto\Resources\MenuResource\Pages;

use App\Filament\Resto\Resources\MenuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMenu extends EditRecord
{
    protected static string $resource = MenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
