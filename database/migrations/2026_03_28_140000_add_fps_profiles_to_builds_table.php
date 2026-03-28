<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('builds', function (Blueprint $table): void {
            $table->json('fps_profiles')->nullable()->after('fps_score');
        });

        $defaultGame = DB::table('fps_games')
            ->orderByDesc('is_default')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->value('key');

        $defaultDisplay = DB::table('fps_displays')
            ->orderByDesc('is_default')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->value('key');

        $defaultPreset = DB::table('fps_presets')
            ->orderByDesc('is_default')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->value('key');

        if (! $defaultGame || ! $defaultDisplay || ! $defaultPreset) {
            return;
        }

        $builds = DB::table('builds')->select('id', 'fps_score')->get();
        $now = now();

        foreach ($builds as $build) {
            $profile = [[
                'game' => (string) $defaultGame,
                'display' => (string) $defaultDisplay,
                'preset' => (string) $defaultPreset,
                'fps' => max(1, (int) ($build->fps_score ?? 90)),
            ]];

            DB::table('builds')
                ->where('id', $build->id)
                ->update([
                    'fps_profiles' => json_encode($profile, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    'updated_at' => $now,
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('builds', function (Blueprint $table): void {
            $table->dropColumn('fps_profiles');
        });
    }
};
