<?php

namespace Tests\Feature;

use App\Models\Build;
use App\Models\Order;
use App\Models\User;
use App\Notifications\NewOrderPlacedNotification;
use App\Notifications\OrderTrackingCredentialsNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class OrderTrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_generates_tracking_credentials_for_customer(): void
    {
        $this->withoutMiddleware();
        Notification::fake();

        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $build = Build::query()->create([
            'slug' => 'order-tracking-build',
            'tone' => 'violet',
            'name' => 'Ігровий ПК "Phantom"',
            'product_code' => '571111',
            'gpu' => 'RTX 4070 Super',
            'cpu' => 'Ryzen 7 7700',
            'ram' => '32GB DDR5',
            'storage' => '1TB NVMe',
            'price' => 69990,
            'fps_score' => 146,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->postJson(route('cart.checkout'), [
            'customer_name' => 'Test User',
            'phone' => '+380631112233',
            'email' => 'customer@example.com',
            'messenger_contact' => '@testuser',
            'comment' => 'Call before shipping.',
            'payment_method' => 'cash_on_delivery',
            'items' => [
                [
                    'slug' => $build->slug,
                    'name' => $build->name,
                    'price' => $build->price,
                    'quantity' => 1,
                    'tone' => $build->tone,
                    'url' => route('product.show', ['slug' => $build->slug]),
                    'configuration' => [],
                    'configurationSummary' => [],
                ],
            ],
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure([
                'message',
                'order_number',
                'tracking_password',
                'tracking_url',
                'email_sent',
            ]);

        $order = Order::query()->with('items')->firstOrFail();

        $this->assertNotEmpty($order->number);
        $this->assertNotEmpty($order->tracking_password);
        $this->assertSame('customer@example.com', $order->email);
        $this->assertSame(1, $order->items->count());
        $this->assertTrue($order->matchesTrackingCredentials('+38 (063) 111-22-33', (string) $response->json('tracking_password')));

        Notification::assertSentTo([$admin], NewOrderPlacedNotification::class);
        Notification::assertSentOnDemand(OrderTrackingCredentialsNotification::class);
    }

    public function test_order_tracking_page_requires_matching_order_number_phone_and_password(): void
    {
        $order = Order::query()->create([
            'number' => 'KP-260406-00001',
            'status' => Order::STATUS_SHIPPED,
            'ordered_at' => now(),
            'customer_name' => 'Tracking User',
            'phone' => '+380631112233',
            'tracking_password' => 'AB12CD34',
            'shipping_ttn' => '20451001122334',
            'payment_method' => 'cash_on_delivery',
            'total_amount' => 54321,
            'currency' => 'UAH',
        ]);

        $order->items()->create([
            'build_slug' => 'tracking-build',
            'build_name' => 'Ігровий ПК "Nova"',
            'unit_price' => 54321,
            'quantity' => 1,
            'line_total' => 54321,
            'meta' => [
                'configuration_summary' => [
                    'Відеокарта: RTX 4070 SUPER',
                ],
            ],
        ]);

        $this->from(route('orders.track'))->post(route('orders.track.lookup'), [
            'number' => $order->number,
            'phone' => '063 111 22 33',
            'tracking_password' => 'wrong-pass',
        ])
            ->assertRedirect(route('orders.track'))
            ->assertSessionHasErrors('credentials');

        $this->post(route('orders.track.lookup'), [
            'number' => $order->number,
            'phone' => '063 111 22 33',
            'tracking_password' => 'AB12CD34',
        ])
            ->assertOk()
            ->assertSee($order->number)
            ->assertSee('20451001122334')
            ->assertSeeText('Ігровий ПК "Nova"');
    }
}
