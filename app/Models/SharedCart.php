<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SharedCart extends Model
{
    protected $fillable = [
        'token',
        'payload',
        'expires_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'expires_at' => 'datetime',
    ];

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('expires_at', '<=', now());
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('expires_at', '>', now());
    }

    public function getItemsCountAttribute(): int
    {
        return count($this->payload ?? []);
    }

    public function getIsExpiredAttribute(): bool
    {
        return (bool) $this->expires_at?->isPast();
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->is_expired ? 'Прострочено' : 'Активне';
    }

    public function getSharedUrlAttribute(): string
    {
        return route('cart.shared', ['token' => $this->token]);
    }
}
