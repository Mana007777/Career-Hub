<?php

namespace App\Filament\Resources\SubSpecialtyResource\Pages;

use App\Filament\Resources\SubSpecialtyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubSpecialty extends EditRecord
{
    protected static string $resource = SubSpecialtyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
