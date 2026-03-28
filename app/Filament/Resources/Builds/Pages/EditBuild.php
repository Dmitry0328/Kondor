<?php

namespace App\Filament\Resources\Builds\Pages;

use App\Filament\Resources\Builds\BuildResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBuild extends EditRecord
{
    protected static string $resource = BuildResource::class;

    protected mixed $pendingCoverUpload = null;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data = [
            ...$data,
            'cover_upload' => BuildResource::coverImagePathForSlug($data['slug'] ?? null),
            ...BuildResource::expandAboutForForm($data['about'] ?? null),
        ];

        return BuildResource::expandFpsProfilesForForm($data);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->pendingCoverUpload = $data['cover_upload'] ?? null;

        unset($data['cover_upload']);

        $data = BuildResource::collapseAboutFromForm($data);

        return BuildResource::normalizeFpsProfilesFromForm($data);
    }

    protected function afterSave(): void
    {
        BuildResource::syncCoverImage($this->getRecord(), $this->pendingCoverUpload);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
