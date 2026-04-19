<?php

namespace Tests\Feature;

use App\Models\Build;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompareBuildsPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_compare_page_renders_only_first_three_unique_builds(): void
    {
        $first = Build::query()->create([
            'slug' => 'compare-alpha',
            'tone' => 'violet',
            'name' => 'Compare Alpha',
            'product_code' => '570101',
            'price' => 70000,
            'fps_score' => 120,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $second = Build::query()->create([
            'slug' => 'compare-beta',
            'tone' => 'violet',
            'name' => 'Compare Beta',
            'product_code' => '570102',
            'price' => 80000,
            'fps_score' => 130,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $third = Build::query()->create([
            'slug' => 'compare-gamma',
            'tone' => 'violet',
            'name' => 'Compare Gamma',
            'product_code' => '570103',
            'price' => 90000,
            'fps_score' => 140,
            'sort_order' => 3,
            'is_active' => true,
        ]);

        $fourth = Build::query()->create([
            'slug' => 'compare-delta',
            'tone' => 'violet',
            'name' => 'Compare Delta',
            'product_code' => '570104',
            'price' => 100000,
            'fps_score' => 150,
            'sort_order' => 4,
            'is_active' => true,
        ]);

        $response = $this->get(route('catalog.compare', [
            'items' => implode(',', [
                $first->slug,
                $second->slug,
                $first->slug,
                $third->slug,
                $fourth->slug,
            ]),
        ]));

        $response->assertOk();
        $response->assertSee('Compare Alpha');
        $response->assertSee('Compare Beta');
        $response->assertSee('Compare Gamma');
        $response->assertDontSee('Compare Delta');
    }

    public function test_compare_page_shows_empty_state_without_selected_builds(): void
    {
        $response = $this->get(route('catalog.compare'));

        $response->assertOk();
        $response->assertSee('Порівняння поки порожнє');
    }
}
