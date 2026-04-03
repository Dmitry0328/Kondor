<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('builds', function (Blueprint $table): void {
            $table->id();
            $table->string('slug')->unique();
            $table->string('tone', 32)->default('violet');
            $table->string('name');
            $table->string('gpu');
            $table->text('cpu');
            $table->string('ram');
            $table->string('storage');
            $table->unsignedInteger('price')->default(0);
            $table->unsignedInteger('fps_score')->default(90);
            $table->json('product_specs')->nullable();
            $table->json('about')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $now = now();

        $rows = collect(config('kondor_storefront.builds', []))
            ->values()
            ->map(function (array $build, int $index) use ($now): array {
                return [
                    'slug' => $build['slug'],
                    'tone' => $build['tone'] ?? 'violet',
                    'name' => $build['name'],
                    'gpu' => $build['gpu'],
                    'cpu' => $build['cpu'],
                    'ram' => $build['ram'],
                    'storage' => $build['storage'],
                    'price' => (int) preg_replace('/\D+/', '', (string) ($build['price'] ?? '0')),
                    'fps_score' => (int) ($build['fps_score'] ?? 90),
                    'product_specs' => isset($build['product_specs'])
                        ? json_encode($build['product_specs'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                        : null,
                    'about' => isset($build['about'])
                        ? json_encode($build['about'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                        : null,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            })
            ->all();

        if ($rows !== []) {
            DB::table('builds')->insert($rows);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('builds');
    }
};
