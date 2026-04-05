<?php

namespace Tests\Feature;

use App\Models\Accessory;
use App\Models\Build;
use App\Models\Component;
use App\Models\SharedBuildLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductShareTest extends TestCase
{
    use RefreshDatabase;

    public function test_shared_build_keeps_zero_price_component_changes_and_accessories(): void
    {
        $this->withoutMiddleware();

        $baseGpu = Component::query()->create([
            'type' => 'gpu',
            'name' => 'Sapphire Pure Radeon RX 7800 XT',
            'slug' => 'test-sapphire-pure-rx-7800-xt',
            'is_active' => true,
        ]);

        $upgradedGpu = Component::query()->create([
            'type' => 'gpu',
            'name' => 'MSI GeForce RTX 4060 Ti Ventus 2X 16G',
            'slug' => 'test-msi-rtx-4060-ti-16g-ventus-2x',
            'is_active' => true,
        ]);

        $keyboard = Accessory::query()->create([
            'type' => 'keyboard',
            'name' => 'Kondor Orion',
            'slug' => 'test-kondor-orion',
            'price' => 5550,
            'is_active' => true,
        ]);

        $build = Build::query()->create([
            'slug' => 'test-share-nova',
            'tone' => 'violet',
            'name' => 'Ігровий ПК "Nova"',
            'product_code' => '579902',
            'price' => 82990,
            'fps_score' => 120,
            'sort_order' => 1,
            'is_active' => true,
            'base_components' => [
                'gpu' => $baseGpu->getKey(),
            ],
            'configurator_groups' => [
                [
                    'key' => 'gpu',
                    'slot' => 'gpu',
                    'title' => 'Заміна відеокарти',
                    'options' => [
                        [
                            'key' => 'upgrade-4060-ti',
                            'component_id' => $upgradedGpu->getKey(),
                            'label' => $upgradedGpu->name,
                            'price_delta' => 0,
                            'is_default' => false,
                            'is_active' => true,
                        ],
                    ],
                ],
            ],
        ]);

        $response = $this->postJson(route('product.share', ['slug' => $build->slug]), [
            'selection' => [
                'gpu' => 'upgrade-4060-ti',
                'accessory_keyboard' => $keyboard->slug,
            ],
        ]);

        $response->assertOk();

        $sharedBuild = SharedBuildLink::query()->firstOrFail();
        $payload = is_array($sharedBuild->payload) ? $sharedBuild->payload : [];

        $this->assertSame('upgrade-4060-ti', $payload['selection']['gpu'] ?? null);
        $this->assertSame($keyboard->slug, $payload['selection']['accessory_keyboard'] ?? null);
        $this->assertSame(5550, $payload['additional_price'] ?? null);
        $this->assertSame(88540, $payload['total_price'] ?? null);
        $this->assertContains('Відеокарта: MSI GeForce RTX 4060 Ti Ventus 2X 16G', $payload['summary'] ?? []);
        $this->assertContains('Клавіатури: Kondor Orion +5 550 грн', $payload['summary'] ?? []);
    }
}
