<?php

namespace App\Filament\Resources\ChatResource\Pages;

use App\Filament\Resources\ChatResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewChat extends ViewRecord
{
    protected static string $resource = ChatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
