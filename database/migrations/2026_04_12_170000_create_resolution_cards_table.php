<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resolution_cards', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->string('label');
            $table->string('eyebrow')->nullable();
            $table->text('description')->nullable();
            $table->string('accent_color', 32)->default('#7b42ff');
            $table->string('image_path')->nullable();
            $table->string('button_label')->default('Дивитися збірки');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('resolution_cards')->insert([
            [
                'key' => 'full-hd',
                'label' => 'Full HD',
                'eyebrow' => 'Starter tier',
                'description' => 'Для 1920x1080, кіберспортивних ігор та комфортного щоденного геймінгу.',
                'accent_color' => '#45d9ff',
                'button_label' => 'Дивитися збірки',
                'sort_order' => 10,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'full-hd-plus',
                'label' => 'Full HD+',
                'eyebrow' => 'High refresh',
                'description' => 'Для Full HD на високих/ультра налаштуваннях і моніторів із підвищеною герцовкою.',
                'accent_color' => '#8b5cf6',
                'button_label' => 'Дивитися збірки',
                'sort_order' => 20,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => '2k',
                'label' => '2K',
                'eyebrow' => 'Balanced power',
                'description' => 'Для 2560x1440, сюжетних AAA-ігор і більш вимогливих графічних сценаріїв.',
                'accent_color' => '#ff8a3d',
                'button_label' => 'Дивитися збірки',
                'sort_order' => 30,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => '4k',
                'label' => '4K',
                'eyebrow' => 'Flagship tier',
                'description' => 'Для 3840x2160, запасу по продуктивності та преміального рівня якості.',
                'accent_color' => '#ff4f8b',
                'button_label' => 'Дивитися збірки',
                'sort_order' => 40,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('resolution_cards');
    }
};
