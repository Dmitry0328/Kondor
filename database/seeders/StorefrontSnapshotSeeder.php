<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class StorefrontSnapshotSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/storefront_snapshot.json');

        if (! File::exists($path)) {
            $this->command?->warn('Snapshot file not found: ' . $path);

            return;
        }

        $payload = json_decode((string) File::get($path), true);

        if (! is_array($payload)) {
            $this->command?->warn('Snapshot file is invalid JSON.');

            return;
        }

        DB::transaction(function () use ($payload): void {
            $this->seedUsers((array) ($payload['users'] ?? []));
            $this->upsertTable('site_settings', (array) ($payload['site_settings'] ?? []), ['key']);
            $this->upsertTable('fps_games', (array) ($payload['fps_games'] ?? []), ['key']);
            $this->upsertTable('fps_displays', (array) ($payload['fps_displays'] ?? []), ['key']);
            $this->upsertTable('fps_presets', (array) ($payload['fps_presets'] ?? []), ['key']);
            $this->upsertTable('resolution_cards', (array) ($payload['resolution_cards'] ?? []), ['key']);
            $this->upsertTable('builds', (array) ($payload['builds'] ?? []), ['slug']);
            $this->upsertTable('components', (array) ($payload['components'] ?? []), ['slug']);
            $this->upsertTable('accessories', (array) ($payload['accessories'] ?? []), ['slug']);
            $this->seedSiteImages((array) ($payload['site_images'] ?? []));
        });
    }

    protected function seedUsers(array $rows): void
    {
        if (! Schema::hasTable('users') || $rows === []) {
            return;
        }

        foreach ($rows as $row) {
            if (! is_array($row) || trim((string) ($row['email'] ?? '')) === '') {
                continue;
            }

            DB::table('users')->updateOrInsert(
                ['email' => (string) $row['email']],
                $this->filterColumns('users', [
                    'name' => $row['name'] ?? 'Admin',
                    'email_verified_at' => $row['email_verified_at'] ?? null,
                    'is_admin' => (bool) ($row['is_admin'] ?? false),
                    'password' => $row['password'] ?? null,
                    'remember_token' => $row['remember_token'] ?? null,
                    'created_at' => $row['created_at'] ?? now(),
                    'updated_at' => $row['updated_at'] ?? now(),
                ]),
            );
        }
    }

    protected function seedSiteImages(array $rows): void
    {
        if (! Schema::hasTable('site_images') || $rows === []) {
            return;
        }

        foreach ($rows as $row) {
            if (! is_array($row) || trim((string) ($row['key'] ?? '')) === '') {
                continue;
            }

            DB::table('site_images')->updateOrInsert(
                ['key' => (string) $row['key']],
                $this->filterColumns('site_images', [
                    'disk' => $row['disk'] ?? 'public',
                    'path' => $row['path'] ?? null,
                    'updated_by' => null,
                    'created_at' => $row['created_at'] ?? now(),
                    'updated_at' => $row['updated_at'] ?? now(),
                ]),
            );
        }
    }

    protected function upsertTable(string $table, array $rows, array $uniqueBy): void
    {
        if (! Schema::hasTable($table) || $rows === []) {
            return;
        }

        $prepared = collect($rows)
            ->filter(fn ($row): bool => is_array($row))
            ->map(fn (array $row): array => $this->filterColumns($table, $row))
            ->filter(fn (array $row): bool => $row !== [])
            ->values()
            ->all();

        if ($prepared === []) {
            return;
        }

        $updateColumns = array_values(array_diff(array_keys($prepared[0]), $uniqueBy, ['id']));

        DB::table($table)->upsert($prepared, $uniqueBy, $updateColumns);
    }

    protected function filterColumns(string $table, array $row): array
    {
        $columns = Schema::getColumnListing($table);

        return collect($row)
            ->except(['id'])
            ->filter(fn ($value, $key): bool => in_array($key, $columns, true))
            ->all();
    }
}
