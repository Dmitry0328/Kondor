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
            'fps_games' => $this->tablePayload('fps_games', ['id', 'key', 'name', 'badge', 'accent', 'scene_from', 'scene_to', 'sort_order', 'is_active', 'is_default', 'created_at', 'updated_at']),
            'fps_displays' => $this->tablePayload('fps_displays', ['id', 'key', 'name', 'mobile_name', 'sort_order', 'is_active', 'is_default', 'created_at', 'updated_at']),
            'fps_presets' => $this->tablePayload('fps_presets', ['id', 'key', 'name', 'sort_order', 'is_active', 'is_default', 'created_at', 'updated_at']),
            'builds' => $this->buildsPayload(),
            'components' => $this->componentsPayload(),
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
            'created_at',
            'updated_at',
        ];

        if (Schema::hasColumn('builds', 'base_components')) {
            $columns[] = 'base_components';
        }

        if (Schema::hasColumn('builds', 'configurator_groups')) {
            $columns[] = 'configurator_groups';
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

        return DB::table($table)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get($columns)
            ->map(fn ($row): array => (array) $row)
            ->all();
    }
}
