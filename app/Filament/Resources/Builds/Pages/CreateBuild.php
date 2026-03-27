<?php

namespace App\Filament\Resources\Builds\Pages;

use App\Filament\Resources\Builds\BuildResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBuild extends CreateRecord
{
    protected static string $resource = BuildResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return BuildResource::collapseAboutFromForm($data);
    }
}
