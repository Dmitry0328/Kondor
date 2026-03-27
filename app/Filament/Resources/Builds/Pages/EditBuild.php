<?php

namespace App\Filament\Resources\Builds\Pages;

use App\Filament\Resources\Builds\BuildResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBuild extends EditRecord
{
    protected static string $resource = BuildResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return [
            ...$data,
            ...BuildResource::expandAboutForForm($data['about'] ?? null),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return BuildResource::collapseAboutFromForm($data);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
