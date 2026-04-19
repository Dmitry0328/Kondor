<?php

namespace Tests\Feature;

use App\Support\OnlineVisitors;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OnlineVisitorsPingTest extends TestCase
{
    use RefreshDatabase;

    public function test_ping_returns_current_online_count(): void
    {
        $firstResponse = $this
            ->withServerVariables([
                'REMOTE_ADDR' => '10.10.10.1',
                'HTTP_USER_AGENT' => 'Browser-A',
            ])
            ->postJson(route('online-visitors.ping'), [
                'context' => 'storefront',
            ]);

        $firstResponse
            ->assertOk()
            ->assertJsonPath('count', 1);

        $this->flushSession();

        $secondResponse = $this
            ->withServerVariables([
                'REMOTE_ADDR' => '10.10.10.1',
                'HTTP_USER_AGENT' => 'Browser-B',
            ])
            ->postJson(route('online-visitors.ping'), [
                'context' => 'admin',
            ]);

        $secondResponse
            ->assertOk()
            ->assertJsonPath('count', 2)
            ->assertJsonPath('window_minutes', OnlineVisitors::ACTIVE_WINDOW_MINUTES);
    }
}
