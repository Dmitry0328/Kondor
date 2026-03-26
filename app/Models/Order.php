<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'number',
        'status',
        'customer_name',
        'phone',
        'messenger_contact',
        'email',
        'comment',
        'payment_method',
        'total_amount',
        'currency',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'processing' => 'В роботі',
            'completed' => 'Завершене',
            'cancelled' => 'Скасоване',
            default => 'Нове',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'processing' => 'warning',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'info',
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'cash_on_delivery' => 'Оплата при отриманні',
            default => $this->payment_method,
        };
    }
}
