<?php

namespace App\Filament\Resources\CareerJobResource\Pages;

use App\Filament\Resources\CareerJobResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCareerJob extends EditRecord
{
    protected static string $resource = CareerJobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
