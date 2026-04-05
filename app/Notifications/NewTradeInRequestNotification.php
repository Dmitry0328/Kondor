<?php

namespace App\Notifications;

use App\Filament\Resources\TradeInRequests\TradeInRequestResource;
use App\Models\TradeInRequest;
use Filament\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Support\Icons\Heroicon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewTradeInRequestNotification extends Notification
{
    use Queueable;

    public function __construct(protected TradeInRequest $tradeInRequest) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title('Нова заявка на трейд-ін #' . $this->tradeInRequest->getKey())
            ->body($this->tradeInRequest->customer_name . ' • ' . $this->tradeInRequest->target_build_label)
            ->icon(Heroicon::OutlinedComputerDesktop)
            ->iconColor('warning')
            ->actions([
                Action::make('viewTradeInRequest')
                    ->label('Відкрити')
                    ->button()
                    ->markAsRead()
                    ->url(TradeInRequestResource::getUrl('view', ['record' => $this->tradeInRequest], panel: 'admin')),
            ])
            ->getDatabaseMessage();
    }
}
