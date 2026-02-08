<?php

namespace App\Filament\Resources\UserSuspensionResource\Pages;

use App\Filament\Resources\UserSuspensionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUserSuspension extends ViewRecord
{
    protected static string $resource = UserSuspensionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
