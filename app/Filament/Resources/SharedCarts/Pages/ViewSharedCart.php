<?php

namespace App\Filament\Resources\SharedCarts\Pages;

use App\Filament\Resources\SharedCarts\SharedCartResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSharedCart extends ViewRecord
{
    protected static string $resource = SharedCartResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('openSharedLink')
                ->label('Відкрити посилання')
                ->url(fn (): string => $this->record->shared_url, shouldOpenInNewTab: true),
            DeleteAction::make(),
        ];
    }
}
