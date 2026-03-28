<?php

namespace App\Filament\Resources\FpsPresets\Pages;

use App\Filament\Resources\FpsPresets\FpsPresetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageFpsPresets extends ManageRecords
{
    protected static string $resource = FpsPresetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
