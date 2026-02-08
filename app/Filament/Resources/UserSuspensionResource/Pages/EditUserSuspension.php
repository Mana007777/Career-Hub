<?php

namespace App\Filament\Resources\UserSuspensionResource\Pages;

use App\Filament\Resources\UserSuspensionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserSuspension extends EditRecord
{
    protected static string $resource = UserSuspensionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
