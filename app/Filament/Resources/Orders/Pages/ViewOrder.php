<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            OrderResource::makeStatusAction(Order::STATUS_PROCESSING, 'В роботу', Heroicon::OutlinedClock, 'warning'),
            OrderResource::makeStatusAction(Order::STATUS_SHIPPED, 'Відправити', Heroicon::OutlinedTruck, 'primary'),
            OrderResource::makeStatusAction(Order::STATUS_COMPLETED, 'Завершити', Heroicon::OutlinedCheckCircle, 'success'),
            OrderResource::makeStatusAction(Order::STATUS_CANCELLED, 'Скасувати', Heroicon::OutlinedXCircle, 'danger'),
        ];
    }
}
