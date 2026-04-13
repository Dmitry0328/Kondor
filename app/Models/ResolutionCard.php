<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ResolutionCard extends Model
{
    protected $fillable = [
        'key',
        'label',
        'eyebrow',
        'description',
        'accent_color',
        'image_path',
        'button_label',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function getImageUrlAttribute(): ?string
    {
        $path = trim((string) ($this->image_path ?? ''));

        if ($path === '') {
            return null;
        }

        return Storage::disk('public')->url($path);
    }
}
