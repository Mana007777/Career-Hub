<?php

namespace App\Filament\Resources\CareerJobResource\Pages;

use App\Filament\Resources\CareerJobResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCareerJob extends ViewRecord
{
    protected static string $resource = CareerJobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
