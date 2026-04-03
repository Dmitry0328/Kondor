<?php

namespace App\Filament\Resources\SharedCarts\Pages;

use App\Filament\Resources\SharedCarts\SharedCartResource;
use Filament\Resources\Pages\ListRecords;

class ListSharedCarts extends ListRecords
{
    protected static string $resource = SharedCartResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
