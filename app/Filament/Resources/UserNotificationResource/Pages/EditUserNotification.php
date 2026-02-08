<?php

namespace App\Filament\Resources\UserNotificationResource\Pages;

use App\Filament\Resources\UserNotificationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserNotification extends EditRecord
{
    protected static string $resource = UserNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
