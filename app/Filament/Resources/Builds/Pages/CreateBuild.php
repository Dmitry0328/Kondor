<?php

namespace App\Filament\Resources\Builds\Pages;

use App\Filament\Resources\Builds\BuildResource;
use App\Models\Build;
use Filament\Resources\Pages\CreateRecord;

class CreateBuild extends CreateRecord
{
    protected static string $resource = BuildResource::class;

    protected ?string $pendingCoverUpload = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->pendingCoverUpload = isset($data['cover_upload']) && is_string($data['cover_upload'])
            ? $data['cover_upload']
            : null;

        unset($data['cover_upload']);

        $data = BuildResource::collapseAboutFromForm($data);

        return BuildResource::normalizeFpsProfilesFromForm($data);
    }

    protected function afterCreate(): void
    {
        if ($this->record instanceof Build) {
            BuildResource::syncCoverImage($this->record, $this->pendingCoverUpload);
        }
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
