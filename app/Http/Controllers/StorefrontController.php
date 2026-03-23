<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    public function home(): View
    {
        $data = $this->baseData();
        $products = $data['products'];

        return view('pages.home', $data + [
            'heroProduct' => $products->firstWhere('slug', 'emerald') ?? $products->first(),
            'featuredProducts' => $products->take(4)->values(),
        ]);
    }

    public function catalog(Request $request): View
    {
        $data = $this->baseData();
        $products = $data['products'];
        $tiers = $data['tiers'];

        $query = trim((string) $request->string('q'));
        $selectedTier = (string) $request->string('tier');

        $filteredProducts = $products
            ->when($selectedTier !== '', fn (Collection $collection) => $collection->where('tier_slug', $selectedTier))
            ->when($query !== '', function (Collection $collection) use ($query) {
                $needle = Str::lower($query);

                return $collection->filter(function (array $product) use ($needle) {
                    $haystack = Str::lower(implode(' ', [
                        $product['name'],
                        $product['tagline'],
                        $product['series'],
                        $product['cpu'],
                        $product['gpu'],
                    ]));

                    return Str::contains($haystack, $needle);
                });
            })
            ->values();

        return view('pages.catalog', $data + [
            'filteredProducts' => $filteredProducts,
            'selectedTier' => $selectedTier,
            'selectedTierName' => $tiers->firstWhere('slug', $selectedTier)['name'] ?? null,
            'searchQuery' => $query,
        ]);
    }

    public function product(string $slug): View
    {
        $data = $this->baseData();
        $products = $data['products'];

        $product = $products->firstWhere('slug', $slug);

        abort_if($product === null, 404);

        $relatedProducts = $products
            ->reject(fn (array $candidate) => $candidate['slug'] === $slug)
            ->take(3)
            ->values();

        return view('pages.product', $data + [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }

    public function contacts(): View
    {
        return view('pages.contacts', $this->baseData());
    }

    protected function baseData(): array
    {
        $store = config('storefront');

        return [
            'store' => $store,
            'categories' => collect($store['categories']),
            'tiers' => collect($store['tiers']),
            'benefits' => collect($store['benefits']),
            'workflow' => collect($store['workflow']),
            'faqs' => collect($store['faqs']),
            'products' => collect($store['products']),
        ];
    }
}
