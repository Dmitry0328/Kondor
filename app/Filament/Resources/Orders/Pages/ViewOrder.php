<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            OrderResource::makeStatusAction('processing', 'В роботу', \Filament\Support\Icons\Heroicon::OutlinedClock, 'warning'),
            OrderResource::makeStatusAction('completed', 'Завершити', \Filament\Support\Icons\Heroicon::OutlinedCheckCircle, 'success'),
            OrderResource::makeStatusAction('cancelled', 'Скасувати', \Filament\Support\Icons\Heroicon::OutlinedXCircle, 'danger'),
        ];
    }
}
