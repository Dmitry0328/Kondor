<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderTrackingCredentialsNotification extends Notification
{
    use Queueable;

    public function __construct(protected Order $order) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Дані для відстеження замовлення ' . ($this->order->number ?: '#' . $this->order->getKey()))
            ->greeting('Дякуємо за замовлення в KondorPC')
            ->line('Ми зберегли для вас доступ до сторінки відстеження замовлення.')
            ->line('Номер замовлення: ' . ($this->order->number ?: '—'))
            ->line('Телефон для входу: ' . ($this->order->phone ?: '—'))
            ->line('Пароль для відстеження: ' . ($this->order->tracking_password ?: '—'))
            ->action('Відстежити замовлення', $this->order->tracking_url)
            ->line('Для перегляду статусу потрібні всі три дані: номер замовлення, телефон і пароль.');
    }
}
