<?php

it('renders a valid sitemap with storefront urls', function () {
    $response = $this->get('/sitemap.xml');

    $response
        ->assertOk()
        ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
        ->assertSee('<?xml version="1.0" encoding="UTF-8"?>', false)
        ->assertSee('<loc>' . route('home') . '</loc>', false)
        ->assertSee('<loc>' . route('catalog') . '</loc>', false)
        ->assertSee('<loc>' . route('accessories.index') . '</loc>', false)
        ->assertSee('<loc>' . route('trade-in') . '</loc>', false)
        ->assertSee('<lastmod>', false)
        ->assertSee('<changefreq>', false)
        ->assertSee('<priority>', false);
});

it('renders robots.txt with sitemap and private route exclusions', function () {
    $response = $this->get('/robots.txt');

    $response
        ->assertOk()
        ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
        ->assertSee('User-agent: *', false)
        ->assertSee('Disallow: /admin', false)
        ->assertSee('Disallow: /order-tracking', false)
        ->assertSee('Disallow: /catalog/shared/', false)
        ->assertSee('Disallow: /cart/shared/', false)
        ->assertSee('Disallow: /catalog/preview/', false)
        ->assertSee('Sitemap: ' . route('sitemap'), false);
});
