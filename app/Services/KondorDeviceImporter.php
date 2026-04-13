<?php

namespace App\Services;

use App\Models\Accessory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class KondorDeviceImporter
{
    public const SOURCE = 'kondor_device';

    protected const BASE_URL = 'https://www.kondordevice.com';

    protected const HOME_URL = 'https://www.kondordevice.com/';

    public function import(bool $deactivateMissing = true): array
    {
        $homeHtml = $this->fetch(static::HOME_URL);
        $slugs = $this->collectProductSlugsFromHomePage($homeHtml);

        $stats = [
            'found' => count($slugs),
            'created' => 0,
            'updated' => 0,
            'skipped' => 0,
            'failed' => 0,
            'deactivated' => 0,
            'errors' => [],
        ];

        $importedAccessoryIds = [];
        $sortOrders = [];

        foreach ($slugs as $slug) {
            $url = static::BASE_URL . '/catalog/' . $slug;

            try {
                $productHtml = $this->fetch($url);
                $payload = $this->parseProductPage($productHtml, $slug, $url);
            } catch (\Throwable $exception) {
                $stats['failed']++;
                $stats['errors'][] = "{$slug}: {$exception->getMessage()}";

                continue;
            }

            if ($payload === null) {
                $stats['skipped']++;

                continue;
            }

            $type = $payload['type'];
            $sortOrders[$type] = ($sortOrders[$type] ?? 0) + 1;
            $payload['sort_order'] = $sortOrders[$type];

            $record = $this->upsertAccessory($payload);
            $importedAccessoryIds[] = (int) $record->getKey();

            if ($record->wasRecentlyCreated) {
                $stats['created']++;
            } else {
                $stats['updated']++;
            }
        }

        if ($deactivateMissing) {
            $stats['deactivated'] = Accessory::query()
                ->where('import_source', static::SOURCE)
                ->when($importedAccessoryIds !== [], fn ($query) => $query->whereNotIn('id', $importedAccessoryIds))
                ->update([
                    'is_active' => false,
                    'last_synced_at' => now(),
                ]);
        }

        return $stats;
    }

    public function collectProductSlugsFromHomePage(string $html): array
    {
        preg_match_all('#/catalog/([a-z0-9\-]+)#i', $html, $matches);

        return collect($matches[1] ?? [])
            ->map(fn (string $slug): string => Str::of($slug)->trim()->lower()->value())
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    public function parseProductPage(string $html, string $slug, string $url): ?array
    {
        $product = $this->extractProductPayloadForSlug($html, $slug);

        if (! is_array($product)) {
            throw new RuntimeException('Не вдалося знайти JSON payload товару.');
        }

        $type = $this->mapSourceType((string) ($product['generalname'] ?? ''));

        if ($type === null) {
            return null;
        }

        $name = $this->cleanText((string) ($product['name'] ?? ''));

        if ($name === '') {
            throw new RuntimeException('У товару відсутня назва.');
        }

        $price = (int) ($product['priceDiscount'] ?? $product['price'] ?? 0);

        if ($price <= 0) {
            $price = (int) ($product['price'] ?? 0);
        }

        return [
            'type' => $type,
            'name' => $name,
            'slug' => $this->resolveImportSlug($slug),
            'vendor' => 'Kondor Device',
            'sku' => $this->cleanText((string) ($product['slug'] ?? $slug)),
            'price' => max(0, $price),
            'summary' => $this->extractSummary($html),
            'specs' => $this->extractSpecs($product),
            'package_items' => $this->extractPackageItems($product),
            'remote_image_urls' => $this->extractImageUrls($product, $html),
            'import_source' => static::SOURCE,
            'external_id' => $this->cleanText((string) ($product['id'] ?? $slug)),
            'source_url' => $url,
            'is_active' => true,
            'last_synced_at' => now(),
        ];
    }

    protected function upsertAccessory(array $payload): Accessory
    {
        $record = Accessory::query()
            ->where('import_source', static::SOURCE)
            ->where('external_id', $payload['external_id'])
            ->first();

        if (! $record instanceof Accessory) {
            $record = Accessory::query()
                ->where('slug', $payload['slug'])
                ->first();
        }

        $attributes = [
            'type' => $payload['type'],
            'name' => $payload['name'],
            'slug' => $this->ensureUniqueSlug($payload['slug'], $record),
            'vendor' => $payload['vendor'],
            'sku' => $payload['sku'],
            'price' => $payload['price'],
            'summary' => $payload['summary'],
            'remote_image_urls' => $payload['remote_image_urls'],
            'specs' => $payload['specs'],
            'package_items' => $payload['package_items'],
            'sort_order' => $payload['sort_order'],
            'is_active' => $payload['is_active'],
            'import_source' => $payload['import_source'],
            'external_id' => $payload['external_id'],
            'source_url' => $payload['source_url'],
            'last_synced_at' => $payload['last_synced_at'],
        ];

        if ($record instanceof Accessory) {
            $record->fill($attributes)->save();

            return $record->refresh();
        }

        return Accessory::query()->create($attributes);
    }

    protected function fetch(string $url): string
    {
        $response = Http::timeout(30)
            ->retry(2, 700, throw: false)
            ->withHeaders([
                'User-Agent' => 'KondorPC Importer/1.0',
                'Accept-Language' => 'uk-UA,uk;q=0.9,en;q=0.8',
            ])
            ->get($url);

        if (! $response->successful()) {
            throw new RuntimeException("Помилка завантаження {$url}: HTTP {$response->status()}");
        }

        return (string) $response->body();
    }

    protected function extractProductPayloadForSlug(string $html, string $slug): ?array
    {
        $chunks = [$html];

        if (preg_match_all('/self\.__next_f\.push\(\[1,"(.*?)"\]\)/s', $html, $matches)) {
            $chunks = array_merge($chunks, array_map(static fn (string $chunk): string => stripcslashes($chunk), $matches[1]));
        }

        $needle = '"slug":"' . addslashes($slug) . '"';

        foreach ($chunks as $chunk) {
            $offset = 0;

            while (($position = strpos($chunk, $needle, $offset)) !== false) {
                $bounds = $this->findEnclosingJsonObject($chunk, $position);

                if ($bounds !== null) {
                    [$start, $end] = $bounds;
                    $json = substr($chunk, $start, $end - $start + 1);
                    $decoded = json_decode($json, true);

                    if (is_array($decoded) && ($decoded['slug'] ?? null) === $slug) {
                        return $decoded;
                    }
                }

                $offset = $position + strlen($needle);
            }
        }

        return null;
    }

    protected function findEnclosingJsonObject(string $html, int $position): ?array
    {
        for ($start = $position; $start >= 0; $start--) {
            if ($html[$start] !== '{') {
                continue;
            }

            $end = $this->findBalancedJsonObjectEnd($html, $start);

            if ($end === null || $end < $position) {
                continue;
            }

            return [$start, $end];
        }

        return null;
    }

    protected function findBalancedJsonObjectEnd(string $html, int $start): ?int
    {
        $length = strlen($html);
        $depth = 0;
        $inString = false;
        $isEscaped = false;

        for ($index = $start; $index < $length; $index++) {
            $character = $html[$index];

            if ($inString) {
                if ($isEscaped) {
                    $isEscaped = false;

                    continue;
                }

                if ($character === '\\') {
                    $isEscaped = true;

                    continue;
                }

                if ($character === '"') {
                    $inString = false;
                }

                continue;
            }

            if ($character === '"') {
                $inString = true;

                continue;
            }

            if ($character === '{') {
                $depth++;

                continue;
            }

            if ($character === '}') {
                $depth--;

                if ($depth === 0) {
                    return $index;
                }
            }
        }

        return null;
    }

    protected function extractSummary(string $html): string
    {
        $summary = $this->extractMetaContent($html, 'description')
            ?? $this->extractMetaContent($html, 'og:description', 'property')
            ?? '';

        return Str::limit($this->cleanText($summary), 240, '...');
    }

    protected function extractMetaContent(string $html, string $meta, string $attribute = 'name'): ?string
    {
        $pattern = sprintf(
            '/<meta[^>]+%s="%s"[^>]+content="([^"]*)"/iu',
            preg_quote($attribute, '/'),
            preg_quote($meta, '/'),
        );

        if (! preg_match($pattern, $html, $matches)) {
            return null;
        }

        return html_entity_decode((string) ($matches[1] ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    protected function extractSpecs(array $product): array
    {
        return collect((array) ($product['chars'] ?? []))
            ->filter(fn ($row): bool => is_array($row))
            ->map(function (array $row): ?array {
                $label = $this->cleanText((string) ($row['name'] ?? ''));
                $value = $this->cleanText((string) ($row['char'] ?? ''));

                if ($label === '' || $value === '') {
                    return null;
                }

                return [
                    'label' => Str::finish(rtrim($label, ':'), ':'),
                    'value' => $value,
                    'is_highlighted' => Str::contains(mb_strtolower($label), ['dpi', 'сенсор', 'свіч', 'підключення', 'частота']),
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    protected function extractPackageItems(array $product): array
    {
        return collect((array) ($product['complect'] ?? []))
            ->filter(fn ($row): bool => is_array($row))
            ->map(function (array $row): ?array {
                $label = $this->cleanText((string) ($row['name'] ?? ''));

                if ($label === '') {
                    return null;
                }

                return [
                    'label' => $label,
                    'icon' => $this->guessPackageIcon($label),
                    'is_highlighted' => false,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    protected function extractImageUrls(array $product, string $html): array
    {
        $urls = collect((array) ($product['coloropts'] ?? []))
            ->filter(fn ($row): bool => is_array($row))
            ->flatMap(fn (array $row): array => (array) ($row['photos'] ?? []))
            ->filter(fn ($row): bool => is_array($row))
            ->map(fn (array $photo): string => $this->cleanText((string) ($photo['url'] ?? '')))
            ->filter()
            ->unique()
            ->values()
            ->all();

        if ($urls !== []) {
            return $urls;
        }

        $fallback = $this->extractMetaContent($html, 'og:image', 'property');

        return $fallback ? [$fallback] : [];
    }

    protected function mapSourceType(string $generalName): ?string
    {
        $value = mb_strtolower($this->cleanText($generalName));

        return match (true) {
            Str::contains($value, 'клавіат') => 'keyboard',
            Str::contains($value, ['мишка', 'миша', 'миші']) => 'mouse',
            Str::contains($value, ['поверхн', 'килим']) => 'pad',
            Str::contains($value, 'кейкап') => 'keycap',
            Str::contains($value, ['кабель', 'aviator']) => 'cable',
            default => null,
        };
    }

    protected function guessPackageIcon(string $label): string
    {
        $value = mb_strtolower($label);

        return match (true) {
            Str::contains($value, ['кабель', 'usb']) => 'cable',
            Str::contains($value, ['свіч', 'switch']) => 'switch',
            Str::contains($value, ['keycap', 'кейкап']) => 'keycap',
            Str::contains($value, ['адаптер', 'донгл', '2.4g']) => 'dongle',
            Str::contains($value, ['інструкц', 'мануал', 'qr']) => 'manual',
            Str::contains($value, ['стікер']) => 'sticker',
            Str::contains($value, ['пулер', 'tool']) => 'tool',
            default => 'generic',
        };
    }

    protected function resolveImportSlug(string $slug): string
    {
        return Str::slug($this->cleanText($slug));
    }

    protected function ensureUniqueSlug(string $slug, ?Accessory $existing = null): string
    {
        $slug = Str::slug($slug);
        $candidate = $slug;
        $suffix = 2;

        while (
            Accessory::query()
                ->where('slug', $candidate)
                ->when($existing instanceof Accessory, fn ($query) => $query->whereKeyNot($existing->getKey()))
                ->exists()
        ) {
            $candidate = $slug . '-' . $suffix;
            $suffix++;
        }

        return $candidate;
    }

    protected function cleanText(string $value): string
    {
        $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $value = str_replace(["\r\n", "\r", "\n", "\t"], ' ', $value);
        $value = preg_replace('/\s+/u', ' ', $value) ?? $value;

        return trim($value);
    }
}
