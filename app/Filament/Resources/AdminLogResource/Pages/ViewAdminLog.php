<?php

namespace App\Filament\Resources\AdminLogResource\Pages;

use App\Filament\Resources\AdminLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAdminLog extends ViewRecord
{
    protected static string $resource = AdminLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
