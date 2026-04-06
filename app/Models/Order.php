<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    public const STATUS_NEW = 'new';

    public const STATUS_CONFIRMED = 'confirmed';

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_READY = 'ready';

    public const STATUS_SHIPPED = 'shipped';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'number',
        'status',
        'ordered_at',
        'customer_name',
        'phone',
        'tracking_password',
        'messenger_contact',
        'email',
        'comment',
        'payment_method',
        'shipping_ttn',
        'total_amount',
        'currency',
        'meta',
    ];

    protected $casts = [
        'ordered_at' => 'datetime',
        'tracking_password' => 'encrypted',
        'meta' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (Order $order): void {
            if (blank($order->status)) {
                $order->status = self::STATUS_NEW;
            }

            if (blank($order->ordered_at)) {
                $order->ordered_at = now();
            }

            if (blank($order->tracking_password)) {
                $order->tracking_password = self::generateTrackingPassword();
            }
        });

        static::created(function (Order $order): void {
            $updates = [];

            if (blank($order->number)) {
                $updates['number'] = self::makeOrderNumber($order);
            }

            if (blank($order->ordered_at)) {
                $updates['ordered_at'] = $order->created_at ?? now();
            }

            if (blank($order->tracking_password)) {
                $updates['tracking_password'] = self::generateTrackingPassword();
            }

            if ($updates !== []) {
                $order->forceFill($updates)->saveQuietly();
            }
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_NEW => 'Нове',
            self::STATUS_CONFIRMED => 'Підтверджене',
            self::STATUS_PROCESSING => 'В роботі',
            self::STATUS_READY => 'Готове до відправки',
            self::STATUS_SHIPPED => 'Відправлене',
            self::STATUS_COMPLETED => 'Завершене',
            self::STATUS_CANCELLED => 'Скасоване',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusOptions()[$this->status] ?? self::statusOptions()[self::STATUS_NEW];
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_CONFIRMED => 'info',
            self::STATUS_PROCESSING => 'warning',
            self::STATUS_READY => 'success',
            self::STATUS_SHIPPED => 'primary',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_CANCELLED => 'danger',
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

    public function getTrackingUrlAttribute(): string
    {
        return route('orders.track', [
            'order' => $this->number,
        ]);
    }

    public function getShipmentTrackingUrlAttribute(): ?string
    {
        $ttn = preg_replace('/\D+/', '', (string) $this->shipping_ttn) ?? '';

        if ($ttn === '') {
            return null;
        }

        return 'https://novaposhta.ua/tracking/?cargo_number=' . $ttn;
    }

    public function matchesTrackingCredentials(string $phone, string $password): bool
    {
        return $this->matchesTrackingPhone($phone)
            && $this->matchesTrackingPassword($password);
    }

    public function matchesTrackingPhone(string $phone): bool
    {
        $expected = self::normalizePhoneForTracking($this->phone);
        $provided = self::normalizePhoneForTracking($phone);

        return $expected !== '' && $expected === $provided;
    }

    public function matchesTrackingPassword(string $password): bool
    {
        $expected = trim((string) ($this->tracking_password ?? ''));
        $provided = trim($password);

        return $expected !== '' && $provided !== '' && hash_equals($expected, $provided);
    }

    public static function normalizePhoneForTracking(?string $value): string
    {
        $digits = preg_replace('/\D+/', '', (string) $value) ?? '';

        if ($digits === '') {
            return '';
        }

        if (Str::startsWith($digits, '380') && strlen($digits) === 12) {
            return '0' . substr($digits, 3);
        }

        if (strlen($digits) > 10) {
            return substr($digits, -10);
        }

        return $digits;
    }

    public static function generateTrackingPassword(int $length = 8): string
    {
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $maxIndex = strlen($alphabet) - 1;
        $password = '';

        for ($index = 0; $index < $length; $index++) {
            $password .= $alphabet[random_int(0, $maxIndex)];
        }

        return $password;
    }

    public static function makeOrderNumber(self $order): string
    {
        $date = ($order->ordered_at ?? $order->created_at ?? now())->format('ymd');

        return 'KP-' . $date . '-' . str_pad((string) $order->getKey(), 5, '0', STR_PAD_LEFT);
    }
}
