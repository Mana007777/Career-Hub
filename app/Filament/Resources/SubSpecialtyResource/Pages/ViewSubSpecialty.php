<?php

namespace App\Filament\Resources\SubSpecialtyResource\Pages;

use App\Filament\Resources\SubSpecialtyResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSubSpecialty extends ViewRecord
{
    protected static string $resource = SubSpecialtyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
