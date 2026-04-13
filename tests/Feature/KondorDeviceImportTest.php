<?php

namespace Tests\Feature;

use App\Models\Accessory;
use App\Services\KondorDeviceImporter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class KondorDeviceImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_importer_creates_accessories_from_kondor_device_pages(): void
    {
        Http::fake([
            'https://www.kondordevice.com/' => Http::response($this->homePageFixture(), 200),
            'https://www.kondordevice.com/catalog/kondor-polaris' => Http::response($this->mouseProductFixture(), 200),
            'https://www.kondordevice.com/catalog/prozori-1' => Http::response($this->keycapProductFixture(), 200),
        ]);

        Artisan::call('accessories:import-kondor-device');

        $mouse = Accessory::query()->where('slug', 'kondor-polaris')->firstOrFail();
        $keycap = Accessory::query()->where('slug', 'prozori-1')->firstOrFail();

        $this->assertSame(KondorDeviceImporter::SOURCE, $mouse->import_source);
        $this->assertSame('mouse', $mouse->type);
        $this->assertSame(1699, $mouse->price);
        $this->assertSame('https://www.kondordevice.com/catalog/kondor-polaris', $mouse->source_url);
        $this->assertCount(2, (array) $mouse->remote_image_urls);
        $this->assertSame('DPI:', $mouse->specs[0]['label']);
        $this->assertSame('24000 DPI', $mouse->specs[0]['value']);
        $this->assertSame('cable', $mouse->package_items[1]['icon']);
        $this->assertTrue($mouse->is_active);
        $this->assertNotNull($mouse->last_synced_at);

        $this->assertSame('keycap', $keycap->type);
        $this->assertSame(799, $keycap->price);
    }

    public function test_importer_deactivates_missing_imported_items(): void
    {
        Accessory::query()->create([
            'type' => 'mouse',
            'name' => 'Old Imported Mouse',
            'slug' => 'old-imported-mouse',
            'import_source' => KondorDeviceImporter::SOURCE,
            'external_id' => 'legacy-source-id',
            'price' => 999,
            'summary' => 'Legacy imported item',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Http::fake([
            'https://www.kondordevice.com/' => Http::response($this->homePageFixture(), 200),
            'https://www.kondordevice.com/catalog/kondor-polaris' => Http::response($this->mouseProductFixture(), 200),
            'https://www.kondordevice.com/catalog/prozori-1' => Http::response($this->keycapProductFixture(), 200),
        ]);

        Artisan::call('accessories:import-kondor-device');

        $this->assertFalse(
            Accessory::query()->where('slug', 'old-imported-mouse')->firstOrFail()->is_active
        );
    }

    protected function homePageFixture(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html lang="uk">
  <body>
    <a href="/catalog/kondor-polaris">Polaris</a>
    <a href="/catalog/prozori-1">Prozori</a>
  </body>
</html>
HTML;
    }

    protected function mouseProductFixture(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html lang="uk">
  <head>
    <meta name="description" content="Бездротова ігрова мишка Kondor Polaris з сенсором PixArt PAW3311 та трьома режимами підключення.">
    <meta property="og:image" content="https://cdn.example.com/polaris-main.webp">
  </head>
  <body>
    <script>
      self.__next_f.push([1,"{\"slug\":\"kondor-polaris\",\"generalname\":\"Мишка\",\"id\":\"mouse-source-id\",\"name\":\"Kondor Polaris\",\"price\":2299,\"priceDiscount\":1699,\"chars\":[{\"name\":\"DPI\",\"char\":\"24000 DPI\"},{\"name\":\"Підключення\",\"char\":\"USB-C / Bluetooth / 2.4Ghz\"}],\"complect\":[{\"name\":\"Мишка Kondor Polaris\"},{\"name\":\"Кабель USB Type-C\"}],\"coloropts\":[{\"photos\":[{\"url\":\"https://cdn.example.com/polaris-main.webp\"},{\"url\":\"https://cdn.example.com/polaris-side.webp\"}]}]}"]);
    </script>
  </body>
</html>
HTML;
    }

    protected function keycapProductFixture(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html lang="uk">
  <head>
    <meta name="description" content="Прозорі кейкапи для кастомних клавіатур.">
    <meta property="og:image" content="https://cdn.example.com/keycaps.webp">
  </head>
  <body>
    <script>
      self.__next_f.push([1,"{\"slug\":\"prozori-1\",\"generalname\":\"Комплект кейкапів\",\"id\":\"keycap-source-id\",\"name\":\"Прозорі\",\"price\":799,\"priceDiscount\":null,\"chars\":[{\"name\":\"Матеріал\",\"char\":\"ABS\"}],\"complect\":[{\"name\":\"Комплект кейкапів\"}],\"coloropts\":[{\"photos\":[{\"url\":\"https://cdn.example.com/keycaps.webp\"}]}]}"]);
    </script>
  </body>
</html>
HTML;
    }
}
