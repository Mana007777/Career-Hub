<?php

namespace App\Filament\Resources\AdminLogResource\Pages;

use App\Filament\Resources\AdminLogResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAdminLog extends CreateRecord
{
    protected static string $resource = AdminLogResource::class;
}
