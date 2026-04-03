<?php

use Illuminate\Database\Migrations\Migration;
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
            $this->seedUsers($payload['users'] ?? []);
            $this->seedFpsGames($payload['fps_games'] ?? []);
            $this->seedFpsDisplays($payload['fps_displays'] ?? []);
            $this->seedFpsPresets($payload['fps_presets'] ?? []);
            $this->seedBuilds($payload['builds'] ?? []);
            $this->seedSiteImages($payload['site_images'] ?? [], $payload['users'] ?? []);
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
            if (Schema::hasTable('site_images')) {
                $keys = collect((array) ($payload['site_images'] ?? []))
                    ->pluck('key')
                    ->filter()
                    ->values()
                    ->all();

                if ($keys !== []) {
                    DB::table('site_images')->whereIn('key', $keys)->delete();
                }
            }

            if (Schema::hasTable('builds')) {
                $slugs = collect((array) ($payload['builds'] ?? []))
                    ->pluck('slug')
                    ->filter()
                    ->values()
                    ->all();

                if ($slugs !== []) {
                    DB::table('builds')->whereIn('slug', $slugs)->delete();
                }
            }

            if (Schema::hasTable('fps_presets')) {
                $keys = collect((array) ($payload['fps_presets'] ?? []))
                    ->pluck('key')
                    ->filter()
                    ->values()
                    ->all();

                if ($keys !== []) {
                    DB::table('fps_presets')->whereIn('key', $keys)->delete();
                }
            }

            if (Schema::hasTable('fps_displays')) {
                $keys = collect((array) ($payload['fps_displays'] ?? []))
                    ->pluck('key')
                    ->filter()
                    ->values()
                    ->all();

                if ($keys !== []) {
                    DB::table('fps_displays')->whereIn('key', $keys)->delete();
                }
            }

            if (Schema::hasTable('fps_games')) {
                $keys = collect((array) ($payload['fps_games'] ?? []))
                    ->pluck('key')
                    ->filter()
                    ->values()
                    ->all();

                if ($keys !== []) {
                    DB::table('fps_games')->whereIn('key', $keys)->delete();
                }
            }

            if (Schema::hasTable('users')) {
                $emails = collect((array) ($payload['users'] ?? []))
                    ->pluck('email')
                    ->filter()
                    ->values()
                    ->all();

                if ($emails !== []) {
                    DB::table('users')->whereIn('email', $emails)->delete();
                }
            }
        });
    }

    protected function seedUsers(array $rows): void
    {
        if (! Schema::hasTable('users') || $rows === []) {
            return;
        }

        $prepared = collect($rows)
            ->filter(fn ($row): bool => is_array($row) && filled($row['email'] ?? null))
            ->map(fn (array $row): array => [
                'name' => (string) ($row['name'] ?? ''),
                'email' => (string) ($row['email'] ?? ''),
                'is_admin' => (bool) ($row['is_admin'] ?? false),
                'email_verified_at' => $row['email_verified_at'] ?? null,
                'password' => (string) ($row['password'] ?? ''),
                'remember_token' => $row['remember_token'] ?? null,
                'created_at' => $row['created_at'] ?? now(),
                'updated_at' => $row['updated_at'] ?? now(),
            ])
            ->values()
            ->all();

        if ($prepared === []) {
            return;
        }

        DB::table('users')->upsert(
            $prepared,
            ['email'],
            ['name', 'is_admin', 'email_verified_at', 'password', 'remember_token', 'updated_at'],
        );
    }

    protected function seedFpsGames(array $rows): void
    {
        if (! Schema::hasTable('fps_games') || $rows === []) {
            return;
        }

        $prepared = collect($rows)
            ->filter(fn ($row): bool => is_array($row) && filled($row['key'] ?? null))
            ->map(fn (array $row): array => [
                'key' => (string) ($row['key'] ?? ''),
                'name' => (string) ($row['name'] ?? ''),
                'badge' => $row['badge'] ?? null,
                'accent' => $row['accent'] ?? null,
                'scene_from' => $row['scene_from'] ?? null,
                'scene_to' => $row['scene_to'] ?? null,
                'sort_order' => (int) ($row['sort_order'] ?? 0),
                'is_active' => (bool) ($row['is_active'] ?? true),
                'is_default' => (bool) ($row['is_default'] ?? false),
                'created_at' => $row['created_at'] ?? now(),
                'updated_at' => $row['updated_at'] ?? now(),
            ])
            ->values()
            ->all();

        if ($prepared === []) {
            return;
        }

        DB::table('fps_games')->upsert(
            $prepared,
            ['key'],
            ['name', 'badge', 'accent', 'scene_from', 'scene_to', 'sort_order', 'is_active', 'is_default', 'updated_at'],
        );
    }

    protected function seedFpsDisplays(array $rows): void
    {
        if (! Schema::hasTable('fps_displays') || $rows === []) {
            return;
        }

        $prepared = collect($rows)
            ->filter(fn ($row): bool => is_array($row) && filled($row['key'] ?? null))
            ->map(fn (array $row): array => [
                'key' => (string) ($row['key'] ?? ''),
                'name' => (string) ($row['name'] ?? ''),
                'mobile_name' => $row['mobile_name'] ?? null,
                'sort_order' => (int) ($row['sort_order'] ?? 0),
                'is_active' => (bool) ($row['is_active'] ?? true),
                'is_default' => (bool) ($row['is_default'] ?? false),
                'created_at' => $row['created_at'] ?? now(),
                'updated_at' => $row['updated_at'] ?? now(),
            ])
            ->values()
            ->all();

        if ($prepared === []) {
            return;
        }

        DB::table('fps_displays')->upsert(
            $prepared,
            ['key'],
            ['name', 'mobile_name', 'sort_order', 'is_active', 'is_default', 'updated_at'],
        );
    }

    protected function seedFpsPresets(array $rows): void
    {
        if (! Schema::hasTable('fps_presets') || $rows === []) {
            return;
        }

        $prepared = collect($rows)
            ->filter(fn ($row): bool => is_array($row) && filled($row['key'] ?? null))
            ->map(fn (array $row): array => [
                'key' => (string) ($row['key'] ?? ''),
                'name' => (string) ($row['name'] ?? ''),
                'sort_order' => (int) ($row['sort_order'] ?? 0),
                'is_active' => (bool) ($row['is_active'] ?? true),
                'is_default' => (bool) ($row['is_default'] ?? false),
                'created_at' => $row['created_at'] ?? now(),
                'updated_at' => $row['updated_at'] ?? now(),
            ])
            ->values()
            ->all();

        if ($prepared === []) {
            return;
        }

        DB::table('fps_presets')->upsert(
            $prepared,
            ['key'],
            ['name', 'sort_order', 'is_active', 'is_default', 'updated_at'],
        );
    }

    protected function seedBuilds(array $rows): void
    {
        if (! Schema::hasTable('builds') || $rows === []) {
            return;
        }

        $prepared = collect($rows)
            ->filter(fn ($row): bool => is_array($row) && filled($row['slug'] ?? null))
            ->map(fn (array $row): array => [
                'slug' => (string) ($row['slug'] ?? ''),
                'tone' => (string) ($row['tone'] ?? 'violet'),
                'name' => (string) ($row['name'] ?? ''),
                'gpu' => (string) ($row['gpu'] ?? ''),
                'cpu' => (string) ($row['cpu'] ?? ''),
                'ram' => (string) ($row['ram'] ?? ''),
                'storage' => (string) ($row['storage'] ?? ''),
                'price' => (int) ($row['price'] ?? 0),
                'fps_score' => (int) ($row['fps_score'] ?? 0),
                'fps_profiles' => $this->normalizeJsonValue($row['fps_profiles'] ?? null),
                'product_specs' => $this->normalizeJsonValue($row['product_specs'] ?? null),
                'about' => $this->normalizeJsonValue($row['about'] ?? null),
                'sort_order' => (int) ($row['sort_order'] ?? 0),
                'is_active' => (bool) ($row['is_active'] ?? true),
                'created_at' => $row['created_at'] ?? now(),
                'updated_at' => $row['updated_at'] ?? now(),
            ])
            ->values()
            ->all();

        if ($prepared === []) {
            return;
        }

        DB::table('builds')->upsert(
            $prepared,
            ['slug'],
            [
                'tone',
                'name',
                'gpu',
                'cpu',
                'ram',
                'storage',
                'price',
                'fps_score',
                'fps_profiles',
                'product_specs',
                'about',
                'sort_order',
                'is_active',
                'updated_at',
            ],
        );
    }

    protected function seedSiteImages(array $rows, array $userRows): void
    {
        if (! Schema::hasTable('site_images') || $rows === []) {
            return;
        }

        $primaryAdminEmail = collect($userRows)
            ->filter(fn ($row): bool => is_array($row))
            ->map(fn (array $row): ?string => isset($row['email']) ? (string) $row['email'] : null)
            ->filter()
            ->first();

        $updatedById = $primaryAdminEmail
            ? DB::table('users')->where('email', $primaryAdminEmail)->value('id')
            : null;

        $prepared = collect($rows)
            ->filter(fn ($row): bool => is_array($row) && filled($row['key'] ?? null) && filled($row['path'] ?? null))
            ->map(fn (array $row): array => [
                'key' => (string) ($row['key'] ?? ''),
                'disk' => (string) ($row['disk'] ?? 'public'),
                'path' => (string) ($row['path'] ?? ''),
                'updated_by' => $updatedById,
                'created_at' => $row['created_at'] ?? now(),
                'updated_at' => $row['updated_at'] ?? now(),
            ])
            ->values()
            ->all();

        if ($prepared === []) {
            return;
        }

        DB::table('site_images')->upsert(
            $prepared,
            ['key'],
            ['disk', 'path', 'updated_by', 'updated_at'],
        );
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
};
