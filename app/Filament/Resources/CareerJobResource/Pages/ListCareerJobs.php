<?php

namespace App\Filament\Resources\CareerJobResource\Pages;

use App\Filament\Resources\CareerJobResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCareerJobs extends ListRecords
{
    protected static string $resource = CareerJobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
