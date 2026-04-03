<?php

namespace App\Notifications;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Support\Icons\Heroicon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewOrderPlacedNotification extends Notification
{
    use Queueable;

    public function __construct(protected Order $order) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title('Нове замовлення ' . ($this->order->number ?: '#' . $this->order->id))
            ->body($this->order->customer_name . ' • ' . $this->formatPrice($this->order->total_amount))
            ->icon(Heroicon::OutlinedShoppingCart)
            ->iconColor('warning')
            ->actions([
                Action::make('viewOrder')
                    ->label('Відкрити')
                    ->button()
                    ->markAsRead()
                    ->url(OrderResource::getUrl('view', ['record' => $this->order], panel: 'admin')),
            ])
            ->getDatabaseMessage();
    }

    protected function formatPrice(int $value): string
    {
        return number_format($value, 0, '', ' ') . ' ₴';
    }
}
