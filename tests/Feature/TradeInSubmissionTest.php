<?php

namespace Tests\Feature;

use App\Models\Build;
use App\Models\SharedBuildLink;
use App\Models\TradeInRequest;
use App\Models\User;
use App\Notifications\NewTradeInRequestNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TradeInSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_trade_in_request_can_be_submitted_with_safe_photo_processing(): void
    {
        $this->withoutMiddleware();
        Storage::fake('public');
        Notification::fake();

        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $build = Build::query()->create([
            'slug' => 'trade-in-target',
            'tone' => 'violet',
            'name' => 'Ігровий ПК "Titan"',
            'product_code' => '570999',
            'gpu' => 'RTX 4070',
            'cpu' => 'Ryzen 7 7800X3D',
            'ram' => '32GB DDR5',
            'storage' => '1TB NVMe',
            'price' => 99999,
            'fps_score' => 144,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $sharedBuildLink = SharedBuildLink::query()->create([
            'token' => str_repeat('a', 32),
            'build_slug' => $build->slug,
            'build_name' => $build->name,
            'payload' => [
                'selection' => [
                    'gpu' => 'gpu-upgrade',
                    'ram' => 'ram-upgrade',
                ],
                'summary' => [
                    'Відеокарта: RTX 4080 SUPER',
                    "Оперативна пам'ять: 64GB DDR5",
                ],
                'additional_price' => 12500,
                'total_price' => 112499,
                'compatibility' => [
                    'is_valid' => true,
                    'messages' => [],
                ],
            ],
            'expires_at' => now()->addDay(),
        ]);

        $response = $this->post(route('trade-in.submit'), [
            'build_slug' => $build->slug,
            'shared_build_token' => $sharedBuildLink->token,
            'customer_name' => 'Test User',
            'phone' => '+380631112233',
            'messenger_contact' => '@testuser',
            'description' => 'Старий ПК у хорошому стані, потрібен трейд-ін на нову збірку.',
            'photos' => [
                UploadedFile::fake()->image('old-pc.jpg', 1800, 1200),
            ],
        ]);

        $response
            ->assertRedirect(route('trade-in', ['build' => $build->slug, 'shared_build' => $sharedBuildLink->token]))
            ->assertSessionHas('tradeInSuccess');

        $this->assertDatabaseHas('trade_in_requests', [
            'build_id' => $build->getKey(),
            'build_slug' => $build->slug,
            'customer_name' => 'Test User',
            'phone' => '+380631112233',
            'status' => 'new',
        ]);

        $tradeInRequest = TradeInRequest::query()->firstOrFail();

        $this->assertNotEmpty($tradeInRequest->photo_paths);
        Storage::disk('public')->assertExists($tradeInRequest->photo_paths[0]);
        $this->assertSame($sharedBuildLink->shared_url, $tradeInRequest->snapshot()['shared_url'] ?? null);
        $this->assertSame(12500, $tradeInRequest->snapshotAdditionalPrice());
        $this->assertSame(112499, $tradeInRequest->snapshotTotalPrice());
        $this->assertSame([
            'Відеокарта: RTX 4080 SUPER',
            "Оперативна пам'ять: 64GB DDR5",
        ], $tradeInRequest->buildSummaryLines());

        Notification::assertSentTo(
            [$admin],
            NewTradeInRequestNotification::class,
        );
    }
}
