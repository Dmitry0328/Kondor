<?php

namespace App\Filament\Resources\ResolutionCards\Pages;

use App\Filament\Resources\ResolutionCards\ResolutionCardResource;
use Filament\Resources\Pages\ManageRecords;

class ManageResolutionCards extends ManageRecords
{
    protected static string $resource = ResolutionCardResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
