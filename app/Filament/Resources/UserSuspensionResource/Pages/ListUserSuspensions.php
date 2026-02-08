<?php

namespace App\Filament\Resources\UserSuspensionResource\Pages;

use App\Filament\Resources\UserSuspensionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserSuspensions extends ListRecords
{
    protected static string $resource = UserSuspensionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
