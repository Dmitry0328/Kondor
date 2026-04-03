<?php

namespace App\Filament\Resources\Builds\Pages;

use App\Filament\Resources\Builds\BuildResource;
use Filament\Resources\Pages\ListRecords;

class ListBuilds extends ListRecords
{
    protected static string $resource = BuildResource::class;
}
