<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fps_games', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->string('badge')->nullable();
            $table->string('accent', 32)->nullable();
            $table->string('scene_from', 32)->nullable();
            $table->string('scene_to', 32)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('fps_displays', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->string('mobile_name')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('fps_presets', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        $now = now();

        DB::table('fps_games')->insert([
            [
                'key' => 'cyberpunk-2077',
                'name' => 'Cyberpunk 2077',
                'badge' => 'Night City benchmark',
                'accent' => '#f4dc39',
                'scene_from' => '#0f182f',
                'scene_to' => '#2b1211',
                'sort_order' => 10,
                'is_active' => true,
                'is_default' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'gta-5',
                'name' => 'GTA 5',
                'badge' => 'Los Santos test',
                'accent' => '#8cff7c',
                'scene_from' => '#10151d',
                'scene_to' => '#183625',
                'sort_order' => 20,
                'is_active' => true,
                'is_default' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'counter-strike-2',
                'name' => 'Counter-Strike 2',
                'badge' => 'Premier smoke test',
                'accent' => '#ffb35c',
                'scene_from' => '#10151d',
                'scene_to' => '#31200f',
                'sort_order' => 30,
                'is_active' => true,
                'is_default' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'fortnite',
                'name' => 'Fortnite',
                'badge' => 'Island benchmark',
                'accent' => '#57d8ff',
                'scene_from' => '#10162a',
                'scene_to' => '#15384a',
                'sort_order' => 40,
                'is_active' => true,
                'is_default' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'valorant',
                'name' => 'Valorant',
                'badge' => 'Ranked preset',
                'accent' => '#ff637b',
                'scene_from' => '#14131d',
                'scene_to' => '#321019',
                'sort_order' => 50,
                'is_active' => true,
                'is_default' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'stalker-2',
                'name' => 'S.T.A.L.K.E.R. 2',
                'badge' => 'Zone benchmark',
                'accent' => '#a3ff63',
                'scene_from' => '#131816',
                'scene_to' => '#2b2210',
                'sort_order' => 60,
                'is_active' => true,
                'is_default' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'red-dead-redemption-2',
                'name' => 'Red Dead Redemption 2',
                'badge' => 'Frontier cinematic',
                'accent' => '#ff8f5a',
                'scene_from' => '#161117',
                'scene_to' => '#3a1b13',
                'sort_order' => 70,
                'is_active' => true,
                'is_default' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'rust',
                'name' => 'Rust',
                'badge' => 'Survival session',
                'accent' => '#ff9759',
                'scene_from' => '#12161d',
                'scene_to' => '#362117',
                'sort_order' => 80,
                'is_active' => true,
                'is_default' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('fps_displays')->insert([
            [
                'key' => '1080p',
                'name' => '1920 x 1080 (Full HD)',
                'mobile_name' => 'Full HD',
                'sort_order' => 10,
                'is_active' => true,
                'is_default' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => '1440p',
                'name' => '2560 x 1440 (2K)',
                'mobile_name' => '2K',
                'sort_order' => 20,
                'is_active' => true,
                'is_default' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => '4k',
                'name' => '3840 x 2160 (4K)',
                'mobile_name' => '4K',
                'sort_order' => 30,
                'is_active' => true,
                'is_default' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('fps_presets')->insert([
            [
                'key' => 'medium',
                'name' => 'Середні',
                'sort_order' => 10,
                'is_active' => true,
                'is_default' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'high',
                'name' => 'Високі',
                'sort_order' => 20,
                'is_active' => true,
                'is_default' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'ultra',
                'name' => 'Ультра',
                'sort_order' => 30,
                'is_active' => true,
                'is_default' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('fps_presets');
        Schema::dropIfExists('fps_displays');
        Schema::dropIfExists('fps_games');
    }
};
