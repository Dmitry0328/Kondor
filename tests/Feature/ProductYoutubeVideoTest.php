<?php

namespace Tests\Feature;

use App\Models\Build;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductYoutubeVideoTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_page_renders_embedded_youtube_video_when_link_is_valid(): void
    {
        $build = Build::query()->create([
            'slug' => 'test-youtube-build',
            'tone' => 'violet',
            'name' => 'YouTube Build',
            'product_code' => '579903',
            'price' => 75999,
            'fps_score' => 144,
            'sort_order' => 1,
            'is_active' => true,
            'youtube_enabled' => true,
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        ]);

        $response = $this->get(route('product.show', ['slug' => $build->slug]));

        $response->assertOk();
        $response->assertSee('https://www.youtube.com/embed/dQw4w9WgXcQ?rel=0', false);
    }

    public function test_product_page_skips_youtube_block_when_block_is_disabled(): void
    {
        $build = Build::query()->create([
            'slug' => 'test-invalid-youtube-build',
            'tone' => 'violet',
            'name' => 'Invalid YouTube Build',
            'product_code' => '579904',
            'price' => 68999,
            'fps_score' => 120,
            'sort_order' => 2,
            'is_active' => true,
            'youtube_enabled' => false,
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        ]);

        $response = $this->get(route('product.show', ['slug' => $build->slug]));

        $response->assertOk();
        $response->assertDontSee('youtube.com/embed/', false);
    }

    public function test_product_page_skips_youtube_block_when_link_is_not_supported(): void
    {
        $build = Build::query()->create([
            'slug' => 'test-unsupported-youtube-build',
            'tone' => 'violet',
            'name' => 'Unsupported YouTube Build',
            'product_code' => '579905',
            'price' => 68999,
            'fps_score' => 120,
            'sort_order' => 3,
            'is_active' => true,
            'youtube_enabled' => true,
            'youtube_url' => 'https://example.com/video',
        ]);

        $response = $this->get(route('product.show', ['slug' => $build->slug]));

        $response->assertOk();
        $response->assertDontSee('youtube.com/embed/', false);
    }
}
