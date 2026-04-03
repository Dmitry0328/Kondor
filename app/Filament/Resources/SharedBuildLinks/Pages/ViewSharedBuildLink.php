<?php

namespace App\Filament\Resources\SharedBuildLinks\Pages;

use App\Filament\Resources\SharedBuildLinks\SharedBuildLinkResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSharedBuildLink extends ViewRecord
{
    protected static string $resource = SharedBuildLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('open')
                ->label('Відкрити посилання')
                ->url(fn (): string => $this->record->shared_url, shouldOpenInNewTab: true),
            DeleteAction::make(),
        ];
    }
}
