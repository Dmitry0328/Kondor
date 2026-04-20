<?php

namespace App\Console\Commands;

use App\Models\Build;
use App\Models\Component;
use App\Models\SiteImage;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

#[Signature('storefront:export-snapshot {--path= : Custom output path for the snapshot JSON}')]
#[Description('Export storefront reference data to database/data/storefront_snapshot.json')]
class ExportStorefrontSnapshot extends Command
{
    public function handle(): int
    {
        $path = $this->resolveSnapshotPath();

        File::ensureDirectoryExists(dirname($path));

        file_put_contents(
            $path,
            json_encode($this->snapshotPayload(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL,
        );

        $this->info("Snapshot exported to: {$path}");

        return self::SUCCESS;
    }

    protected function resolveSnapshotPath(): string
    {
        $customPath = trim((string) $this->option('path'));

        if ($customPath !== '') {
            return str_contains($customPath, ':') || str_starts_with($customPath, DIRECTORY_SEPARATOR)
                ? $customPath
                : base_path($customPath);
        }

        return database_path('data/storefront_snapshot.json');
    }

    protected function snapshotPayload(): array
    {
        return [
            'users' => $this->usersPayload(),
            'site_settings' => $this->tablePayload('site_settings', ['id', 'key', 'value', 'created_at', 'updated_at']),
            'fps_games' => $this->tablePayload('fps_games', ['id', 'key', 'name', 'badge', 'accent', 'scene_from', 'scene_to', 'sort_order', 'is_active', 'is_default', 'created_at', 'updated_at']),
            'fps_displays' => $this->tablePayload('fps_displays', ['id', 'key', 'name', 'mobile_name', 'sort_order', 'is_active', 'is_default', 'created_at', 'updated_at']),
            'fps_presets' => $this->tablePayload('fps_presets', ['id', 'key', 'name', 'sort_order', 'is_active', 'is_default', 'created_at', 'updated_at']),
            'resolution_cards' => $this->resolutionCardsPayload(),
            'builds' => $this->buildsPayload(),
            'components' => $this->componentsPayload(),
            'accessories' => $this->accessoriesPayload(),
            'site_images' => $this->siteImagesPayload(),
        ];
    }

    protected function usersPayload(): array
    {
        if (! Schema::hasTable('users')) {
            return [];
        }

        return DB::table('users')
            ->orderBy('id')
            ->get([
                'id',
                'name',
                'email',
                'is_admin',
                'email_verified_at',
                'password',
                'remember_token',
                'created_at',
                'updated_at',
            ])
            ->map(fn ($row): array => (array) $row)
            ->all();
    }

    protected function buildsPayload(): array
    {
        if (! Schema::hasTable('builds')) {
            return [];
        }

        $columns = [
            'id',
            'slug',
            'tone',
            'name',
            'product_code',
            'gpu',
            'cpu',
            'ram',
            'storage',
            'price',
            'fps_score',
            'fps_profiles',
            'product_specs',
            'about',
            'youtube_enabled',
            'youtube_url',
            'sort_order',
            'is_active',
            'created_at',
            'updated_at',
        ];

        if (Schema::hasColumn('builds', 'base_components')) {
            $columns[] = 'base_components';
        }

        if (Schema::hasColumn('builds', 'configurator_groups')) {
            $columns[] = 'configurator_groups';
        }

        if (Schema::hasColumn('builds', 'resolution_tags')) {
            $columns[] = 'resolution_tags';
        }

        if (Schema::hasColumn('builds', 'case_variants')) {
            $columns[] = 'case_variants';
        }

        return Build::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get($columns)
            ->map(fn (Build $build): array => $build->toArray())
            ->all();
    }

    protected function componentsPayload(): array
    {
        if (! Schema::hasTable('components')) {
            return [];
        }

        return Component::query()
            ->orderBy('type')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (Component $component): array => $component->toArray())
            ->all();
    }

    protected function accessoriesPayload(): array
    {
        if (! Schema::hasTable('accessories')) {
            return [];
        }

        return DB::table('accessories')
            ->orderBy('type')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get([
                'id',
                'type',
                'name',
                'slug',
                'import_source',
                'external_id',
                'source_url',
                'vendor',
                'sku',
                'price',
                'summary',
                'gallery_paths',
                'remote_image_urls',
                'specs',
                'package_items',
                'sort_order',
                'is_active',
                'last_synced_at',
                'created_at',
                'updated_at',
            ])
            ->map(fn ($row): array => (array) $row)
            ->all();
    }

    protected function resolutionCardsPayload(): array
    {
        if (! Schema::hasTable('resolution_cards')) {
            return [];
        }

        return DB::table('resolution_cards')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get([
                'id',
                'key',
                'label',
                'eyebrow',
                'description',
                'accent_color',
                'image_path',
                'button_label',
                'sort_order',
                'is_active',
                'created_at',
                'updated_at',
            ])
            ->map(fn ($row): array => (array) $row)
            ->all();
    }

    protected function siteImagesPayload(): array
    {
        if (! Schema::hasTable('site_images')) {
            return [];
        }

        return SiteImage::query()
            ->orderBy('key')
            ->get([
                'id',
                'key',
                'disk',
                'path',
                'created_at',
                'updated_at',
            ])
            ->map(fn (SiteImage $image): array => $image->toArray())
            ->all();
    }

    protected function tablePayload(string $table, array $columns): array
    {
        if (! Schema::hasTable($table)) {
            return [];
        }

        $query = DB::table($table);

        if (Schema::hasColumn($table, 'sort_order')) {
            $query->orderBy('sort_order');
        }

        return $query
            ->orderBy('id')
            ->get($columns)
            ->map(fn ($row): array => (array) $row)
            ->all();
    }
}
