<?php

namespace Tests\Feature;

use Tests\TestCase;

class CookieConsentBannerTest extends TestCase
{
    public function test_home_page_contains_cookie_consent_banner(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Дозволити cookie на сайті?');
        $response->assertSee('Лише потрібні');
        $response->assertSee('Дозволити всі');
    }
}
