<?php

namespace App\Filament\Resources\Builds\Pages;

use App\Filament\Resources\Builds\BuildResource;
use App\Filament\Resources\Builds\Pages\Concerns\InteractsWithBuildPreview;
use App\Support\BuildImages;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBuild extends EditRecord
{
    use InteractsWithBuildPreview;

    protected static string $resource = BuildResource::class;

    protected array $pendingGalleryUploads = [];

    protected ?bool $forcedPublicationState = null;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data = [
            ...$data,
            'gallery_uploads' => BuildResource::galleryImagePathsForSlug($data['slug'] ?? null),
            ...BuildResource::expandAboutForForm($data['about'] ?? null),
        ];

        $data = BuildResource::expandConfiguratorForForm($data);

        return BuildResource::expandFpsProfilesForForm($data);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->pendingGalleryUploads = BuildImages::normalizeUploadState($data['gallery_uploads'] ?? null);

        unset($data['gallery_uploads']);

        $data = BuildResource::collapseAboutFromForm($data);
        $data = BuildResource::normalizeConfiguratorFromForm($data);
        $data['is_active'] = $this->forcedPublicationState ?? ($data['is_active'] ?? false);
        $this->forcedPublicationState = null;

        return BuildResource::normalizeFpsProfilesFromForm($data);
    }

    protected function afterSave(): void
    {
        BuildResource::syncGalleryImages($this->getRecord(), $this->pendingGalleryUploads);
    }

    protected function getFormActions(): array
    {
        return [
            $this->getPreviewAction(),
            $this->getSaveDraftFormAction(),
            $this->getSaveFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return Action::make('publish')
            ->label('Опублікувати')
            ->keyBindings(['mod+s'])
            ->action(function (): void {
                $this->forcedPublicationState = true;
                $this->save();
                $this->refreshFormData(['is_active']);
            });
    }

    protected function getSaveDraftFormAction(): Action
    {
        return Action::make('saveDraft')
            ->label('Зберегти в чернетку')
            ->color('gray')
            ->action(function (): void {
                $this->forcedPublicationState = false;
                $this->save();
                $this->refreshFormData(['is_active']);
            });
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): ?string
    {
        return static::getResource()::getUrl('edit', ['record' => $this->getRecord()->getKey()], isAbsolute: false);
    }
}
