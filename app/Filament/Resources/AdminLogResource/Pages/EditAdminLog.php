<?php

namespace App\Filament\Resources\AdminLogResource\Pages;

use App\Filament\Resources\AdminLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdminLog extends EditRecord
{
    protected static string $resource = AdminLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
