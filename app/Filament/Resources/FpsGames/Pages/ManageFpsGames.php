<?php

namespace App\Filament\Resources\FpsGames\Pages;

use App\Filament\Resources\FpsGames\FpsGameResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageFpsGames extends ManageRecords
{
    protected static string $resource = FpsGameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
