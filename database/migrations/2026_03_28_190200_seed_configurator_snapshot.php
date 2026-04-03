<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $snapshotPath = database_path('data/storefront_snapshot.json');

        if (! is_file($snapshotPath)) {
            return;
        }

        $payload = json_decode(file_get_contents($snapshotPath), true);

        if (! is_array($payload)) {
            return;
        }

        DB::transaction(function () use ($payload): void {
            $this->seedComponents($payload['components'] ?? []);
            $this->seedBuildConfiguratorData($payload['builds'] ?? []);
        });
    }

    public function down(): void
    {
        $snapshotPath = database_path('data/storefront_snapshot.json');

        if (! is_file($snapshotPath)) {
            return;
        }

        $payload = json_decode(file_get_contents($snapshotPath), true);

        if (! is_array($payload)) {
            return;
        }

        DB::transaction(function () use ($payload): void {
            if (Schema::hasTable('components')) {
                $slugs = collect((array) ($payload['components'] ?? []))
                    ->pluck('slug')
                    ->filter()
                    ->values()
                    ->all();

                if ($slugs !== []) {
                    DB::table('components')->whereIn('slug', $slugs)->delete();
                }
            }

            if (
                Schema::hasTable('builds')
                && Schema::hasColumn('builds', 'base_components')
                && Schema::hasColumn('builds', 'configurator_groups')
            ) {
                $rows = collect((array) ($payload['builds'] ?? []))
                    ->filter(fn ($row): bool => is_array($row) && filled($row['slug'] ?? null))
                    ->pluck('slug')
                    ->values()
                    ->all();

                if ($rows !== []) {
                    DB::table('builds')
                        ->whereIn('slug', $rows)
                        ->update([
                            'base_components' => null,
                            'configurator_groups' => null,
                            'updated_at' => now(),
                        ]);
                }
            }
        });
    }

    protected function seedComponents(array $rows): void
    {
        if (! Schema::hasTable('components') || $rows === []) {
            return;
        }

        $prepared = collect($rows)
            ->filter(fn ($row): bool => is_array($row) && filled($row['slug'] ?? null))
            ->map(fn (array $row): array => [
                'slug' => (string) ($row['slug'] ?? ''),
                'type' => (string) ($row['type'] ?? 'other'),
                'name' => (string) ($row['name'] ?? ''),
                'vendor' => $this->nullableString($row['vendor'] ?? null),
                'sku' => $this->nullableString($row['sku'] ?? null),
                'summary' => $this->nullableString($row['summary'] ?? null),
                'socket' => $this->nullableString($row['socket'] ?? null),
                'ram_type' => $this->nullableString($row['ram_type'] ?? null),
                'form_factor' => $this->nullableString($row['form_factor'] ?? null),
                'supported_mb_form_factors' => $this->normalizeJsonValue($row['supported_mb_form_factors'] ?? null),
                'supported_sockets' => $this->normalizeJsonValue($row['supported_sockets'] ?? null),
                'supported_radiator_sizes' => $this->normalizeJsonValue($row['supported_radiator_sizes'] ?? null),
                'max_gpu_length_mm' => $this->nullableInt($row['max_gpu_length_mm'] ?? null),
                'max_cooler_height_mm' => $this->nullableInt($row['max_cooler_height_mm'] ?? null),
                'gpu_length_mm' => $this->nullableInt($row['gpu_length_mm'] ?? null),
                'gpu_power_w' => $this->nullableInt($row['gpu_power_w'] ?? null),
                'gpu_power_connectors' => $this->nullableInt($row['gpu_power_connectors'] ?? null),
                'cpu_tdp_w' => $this->nullableInt($row['cpu_tdp_w'] ?? null),
                'psu_wattage' => $this->nullableInt($row['psu_wattage'] ?? null),
                'pcie_power_connectors' => $this->nullableInt($row['pcie_power_connectors'] ?? null),
                'radiator_size_mm' => $this->nullableInt($row['radiator_size_mm'] ?? null),
                'memory_modules' => $this->nullableInt($row['memory_modules'] ?? null),
                'memory_capacity_gb' => $this->nullableInt($row['memory_capacity_gb'] ?? null),
                'memory_speed_mhz' => $this->nullableInt($row['memory_speed_mhz'] ?? null),
                'storage_interface' => $this->nullableString($row['storage_interface'] ?? null),
                'meta' => $this->normalizeJsonValue($row['meta'] ?? null),
                'sort_order' => (int) ($row['sort_order'] ?? 0),
                'is_active' => (bool) ($row['is_active'] ?? true),
                'created_at' => $this->normalizeTimestamp($row['created_at'] ?? null),
                'updated_at' => $this->normalizeTimestamp($row['updated_at'] ?? null),
            ])
            ->values()
            ->all();

        if ($prepared === []) {
            return;
        }

        DB::table('components')->upsert(
            $prepared,
            ['slug'],
            [
                'type',
                'name',
                'vendor',
                'sku',
                'summary',
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
                'updated_at',
            ],
        );
    }

    protected function seedBuildConfiguratorData(array $rows): void
    {
        if (
            ! Schema::hasTable('builds')
            || ! Schema::hasColumn('builds', 'base_components')
            || ! Schema::hasColumn('builds', 'configurator_groups')
            || $rows === []
        ) {
            return;
        }

        $prepared = collect($rows)
            ->filter(fn ($row): bool => is_array($row) && filled($row['slug'] ?? null))
            ->map(fn (array $row): array => [
                'slug' => (string) ($row['slug'] ?? ''),
                'base_components' => $this->normalizeJsonValue($row['base_components'] ?? null),
                'configurator_groups' => $this->normalizeJsonValue($row['configurator_groups'] ?? null),
                'updated_at' => $this->normalizeTimestamp($row['updated_at'] ?? null),
            ])
            ->values()
            ->all();

        if ($prepared === []) {
            return;
        }

        foreach ($prepared as $row) {
            DB::table('builds')
                ->where('slug', $row['slug'])
                ->update([
                    'base_components' => $row['base_components'],
                    'configurator_groups' => $row['configurator_groups'],
                    'updated_at' => $row['updated_at'],
                ]);
        }
    }

    protected function normalizeJsonValue(mixed $value): ?string
    {
        if (is_string($value)) {
            $trimmed = trim($value);

            return $trimmed === '' ? null : $trimmed;
        }

        if (is_array($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        return null;
    }

    protected function nullableString(mixed $value): ?string
    {
        $value = trim((string) $value);

        return $value !== '' ? $value : null;
    }

    protected function nullableInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (int) $value;
    }

    protected function normalizeTimestamp(mixed $value): string
    {
        if ($value === null || $value === '') {
            return now()->toDateTimeString();
        }

        try {
            return Carbon::parse((string) $value)->toDateTimeString();
        } catch (\Throwable) {
            return now()->toDateTimeString();
        }
    }
};
