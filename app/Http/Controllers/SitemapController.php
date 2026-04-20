<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\Build;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use XMLWriter;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $writer = new XMLWriter();
        $writer->openMemory();
        $writer->setIndent(true);
        $writer->setIndentString('    ');
        $writer->startDocument('1.0', 'UTF-8');
        $writer->startElement('urlset');
        $writer->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        foreach ($this->urls() as $entry) {
            $writer->startElement('url');
            $writer->writeElement('loc', $entry['loc']);

            if ($entry['lastmod'] !== null) {
                $writer->writeElement('lastmod', $entry['lastmod']);
            }

            if ($entry['changefreq'] !== null) {
                $writer->writeElement('changefreq', $entry['changefreq']);
            }

            if ($entry['priority'] !== null) {
                $writer->writeElement('priority', $entry['priority']);
            }

            $writer->endElement();
        }

        $writer->endElement();
        $writer->endDocument();

        return response($writer->outputMemory(), 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }

    /**
     * @return \Illuminate\Support\Collection<int, array{loc: string, lastmod: ?string, changefreq: ?string, priority: ?string}>
     */
    protected function urls(): Collection
    {
        $now = now()->toAtomString();
        $urls = collect([
            [
                'loc' => route('home'),
                'lastmod' => $now,
                'changefreq' => 'daily',
                'priority' => '1.0',
            ],
            [
                'loc' => route('catalog'),
                'lastmod' => $now,
                'changefreq' => 'daily',
                'priority' => '0.9',
            ],
            [
                'loc' => route('accessories.index'),
                'lastmod' => $now,
                'changefreq' => 'daily',
                'priority' => '0.8',
            ],
            [
                'loc' => route('trade-in'),
                'lastmod' => $now,
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ],
        ]);

        if (Schema::hasTable('builds')) {
            $buildUrls = Build::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->map(function (Build $build) use ($now): ?array {
                    $slug = trim((string) $build->slug);

                    if ($slug === '') {
                        return null;
                    }

                    return [
                        'loc' => route('product.show', ['slug' => $slug]),
                        'lastmod' => optional($build->updated_at)->toAtomString() ?? $now,
                        'changefreq' => 'weekly',
                        'priority' => '0.8',
                    ];
                })
                ->filter();

            $urls = $urls->concat($buildUrls);
        }

        if (Schema::hasTable('accessories')) {
            $accessoryUrls = Accessory::query()
                ->where('is_active', true)
                ->orderBy('type')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->map(function (Accessory $accessory) use ($now): ?array {
                    $slug = trim((string) $accessory->slug);

                    if ($slug === '') {
                        return null;
                    }

                    return [
                        'loc' => route('accessories.show', ['slug' => $slug]),
                        'lastmod' => optional($accessory->updated_at)->toAtomString() ?? $now,
                        'changefreq' => 'weekly',
                        'priority' => '0.7',
                    ];
                })
                ->filter();

            $urls = $urls->concat($accessoryUrls);
        }

        /** @var \Illuminate\Support\Collection<int, array{loc: string, lastmod: ?string, changefreq: ?string, priority: ?string}> $urls */
        return $urls
            ->unique('loc')
            ->values();
    }
}
