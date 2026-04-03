<?php

namespace App\Models;

use App\Support\FpsCatalog;
use Illuminate\Database\Eloquent\Model;

class FpsDisplay extends Model
{
    protected $fillable = [
        'key',
        'name',
        'mobile_name',
        'sort_order',
        'is_active',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saved(static fn (): FpsCatalog => FpsCatalog::flush());
        static::deleted(static fn (): FpsCatalog => FpsCatalog::flush());
    }
}
