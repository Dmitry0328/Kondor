<?php

namespace App\Filament\Resources\FpsDisplays\Pages;

use App\Filament\Resources\FpsDisplays\FpsDisplayResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageFpsDisplays extends ManageRecords
{
    protected static string $resource = FpsDisplayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
