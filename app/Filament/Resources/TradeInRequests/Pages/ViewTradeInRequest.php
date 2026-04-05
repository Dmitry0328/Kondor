<?php

namespace App\Filament\Resources\TradeInRequests\Pages;

use App\Filament\Resources\TradeInRequests\TradeInRequestResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewTradeInRequest extends ViewRecord
{
    protected static string $resource = TradeInRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            TradeInRequestResource::makeStatusAction('processing', 'В роботу', Heroicon::OutlinedClock, 'warning'),
            TradeInRequestResource::makeStatusAction('completed', 'Закрити', Heroicon::OutlinedCheckCircle, 'success'),
            TradeInRequestResource::makeStatusAction('rejected', 'Відхилити', Heroicon::OutlinedXCircle, 'danger'),
        ];
    }
}
