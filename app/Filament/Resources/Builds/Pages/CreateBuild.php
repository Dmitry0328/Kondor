<?php

namespace App\Filament\Resources\Builds\Pages;

use App\Filament\Resources\Builds\BuildResource;
use App\Filament\Resources\Builds\Pages\Concerns\InteractsWithBuildPreview;
use App\Models\Build;
use App\Support\BuildImages;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateBuild extends CreateRecord
{
    use InteractsWithBuildPreview;

    protected static string $resource = BuildResource::class;

    protected array $pendingGalleryUploads = [];

    protected ?bool $forcedPublicationState = null;

    protected function getFormActions(): array
    {
        return [
            $this->getPreviewAction(),
            $this->getSaveDraftFormAction(),
            $this->getCreateFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function getCreateFormAction(): Action
    {
        return Action::make('publish')
            ->label('Опублікувати')
            ->keyBindings(['mod+s'])
            ->action(function (): void {
                $this->forcedPublicationState = true;
                $this->create(false);
            });
    }

    protected function getSaveDraftFormAction(): Action
    {
        return Action::make('saveDraft')
            ->label('Зберегти в чернетку')
            ->color('gray')
            ->action(function (): void {
                $this->forcedPublicationState = false;
                $this->create(false);
            });
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->pendingGalleryUploads = BuildImages::normalizeUploadState($data['gallery_uploads'] ?? null);

        unset($data['gallery_uploads']);

        $data = BuildResource::collapseAboutFromForm($data);
        $data = BuildResource::normalizeConfiguratorFromForm($data);
        $data['is_active'] = $this->forcedPublicationState ?? true;
        $this->forcedPublicationState = null;

        return BuildResource::normalizeFpsProfilesFromForm($data);
    }

    protected function afterCreate(): void
    {
        if ($this->record instanceof Build) {
            BuildResource::syncGalleryImages($this->record, $this->pendingGalleryUploads);
        }
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index', isAbsolute: false);
    }
}
