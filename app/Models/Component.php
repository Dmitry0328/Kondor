<?php

namespace App\Models;

use App\Support\ComponentImages;
use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    protected $fillable = [
        'type',
        'name',
        'slug',
        'vendor',
        'sku',
        'summary',
        'gallery_paths',
        'socket',
        'ram_type',
        'form_factor',
        'supported_mb_form_factors',
        'supported_sockets',
        'supported_radiator_sizes',
        'max_gpu_length_mm',
        'max_cooler_height_mm',
        'gpu_length_mm',
        'gpu_power_w',
        'gpu_power_connectors',
        'cpu_tdp_w',
        'psu_wattage',
        'pcie_power_connectors',
        'radiator_size_mm',
        'memory_modules',
        'memory_capacity_gb',
        'memory_speed_mhz',
        'storage_interface',
        'meta',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'supported_mb_form_factors' => 'array',
            'supported_sockets' => 'array',
            'supported_radiator_sizes' => 'array',
            'gallery_paths' => 'array',
            'meta' => 'array',
            'max_gpu_length_mm' => 'integer',
            'max_cooler_height_mm' => 'integer',
            'gpu_length_mm' => 'integer',
            'gpu_power_w' => 'integer',
            'gpu_power_connectors' => 'integer',
            'cpu_tdp_w' => 'integer',
            'psu_wattage' => 'integer',
            'pcie_power_connectors' => 'integer',
            'radiator_size_mm' => 'integer',
            'memory_modules' => 'integer',
            'memory_capacity_gb' => 'integer',
            'memory_speed_mhz' => 'integer',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (Component $component): void {
            if ($component->wasChanged('slug')) {
                ComponentImages::rename(
                    (string) $component->getOriginal('slug'),
                    (string) $component->slug,
                );
            }
        });

        static::deleted(function (Component $component): void {
            ComponentImages::delete((string) $component->slug);
        });
    }

    public function imageUrl(): string
    {
        return ComponentImages::urlForComponent($this);
    }

    public function imageUrls(): array
    {
        return ComponentImages::urlsForComponent($this);
    }
}
