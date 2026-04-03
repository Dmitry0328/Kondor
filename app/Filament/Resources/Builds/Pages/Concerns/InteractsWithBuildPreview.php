<?php

namespace App\Filament\Resources\Builds\Pages\Concerns;

use App\Models\Build;
use App\Support\BuildPreview;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;

trait InteractsWithBuildPreview
{
    protected function getPreviewAction(): Action
    {
        return Action::make('previewBeforeSave')
            ->label('Перегляд перед збереженням')
            ->icon(Heroicon::OutlinedEye)
            ->color('gray')
            ->action(function (): void {
                $returnUrl = $this->getPreviewRecord()
                    ? static::getResource()::getUrl('edit', ['record' => $this->getPreviewRecord()?->getKey()], isAbsolute: false)
                    : static::getResource()::getUrl('create', isAbsolute: false);

                $token = BuildPreview::store(
                    (array) $this->form->getRawState(),
                    $this->getPreviewRecord(),
                    $returnUrl,
                );

                $this->dispatch('open-admin-build-preview', url: route('product.preview', ['token' => $token]));
            });
    }

    protected function getPreviewRecord(): ?Build
    {
        return $this->record instanceof Build ? $this->record : null;
    }
}
