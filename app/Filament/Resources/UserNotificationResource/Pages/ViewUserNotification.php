<?php

namespace App\Filament\Resources\UserNotificationResource\Pages;

use App\Filament\Resources\UserNotificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUserNotification extends ViewRecord
{
    protected static string $resource = UserNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
