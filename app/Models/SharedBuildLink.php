<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SharedBuildLink extends Model
{
    protected $fillable = [
        'token',
        'build_slug',
        'build_name',
        'payload',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'expires_at' => 'datetime',
        ];
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('expires_at', '<=', now());
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('expires_at', '>', now());
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
        return route('product.shared', ['token' => $this->token]);
    }

    public function getSelectedOptionsCountAttribute(): int
    {
        $selection = $this->payload['selection'] ?? null;

        return is_array($selection) ? count(array_filter($selection)) : 0;
    }
}
