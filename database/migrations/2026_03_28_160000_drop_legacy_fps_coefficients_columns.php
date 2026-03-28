<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('fps_games') && Schema::hasColumn('fps_games', 'difficulty')) {
            Schema::table('fps_games', function (Blueprint $table): void {
                $table->dropColumn('difficulty');
            });
        }

        if (Schema::hasTable('fps_displays') && Schema::hasColumn('fps_displays', 'multiplier')) {
            Schema::table('fps_displays', function (Blueprint $table): void {
                $table->dropColumn('multiplier');
            });
        }

        if (Schema::hasTable('fps_presets') && Schema::hasColumn('fps_presets', 'multiplier')) {
            Schema::table('fps_presets', function (Blueprint $table): void {
                $table->dropColumn('multiplier');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('fps_games') && ! Schema::hasColumn('fps_games', 'difficulty')) {
            Schema::table('fps_games', function (Blueprint $table): void {
                $table->decimal('difficulty', 6, 3)->default(1)->after('name');
            });
        }

        if (Schema::hasTable('fps_displays') && ! Schema::hasColumn('fps_displays', 'multiplier')) {
            Schema::table('fps_displays', function (Blueprint $table): void {
                $table->decimal('multiplier', 6, 3)->default(1)->after('mobile_name');
            });
        }

        if (Schema::hasTable('fps_presets') && ! Schema::hasColumn('fps_presets', 'multiplier')) {
            Schema::table('fps_presets', function (Blueprint $table): void {
                $table->decimal('multiplier', 6, 3)->default(1)->after('name');
            });
        }
    }
};
