<?php

namespace App\Http\Controllers;

use App\Support\SiteSettings;
use App\Support\StorefrontBuilds;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CompareController extends Controller
{
    public function show(Request $request): View|RedirectResponse
    {
        if (! SiteSettings::bool('build.compare.enabled', true)) {
            return redirect()->route('catalog');
        }

        $allBuilds = collect(StorefrontBuilds::all())
            ->filter(fn (array $build): bool => trim((string) ($build['slug'] ?? '')) !== '')
            ->keyBy(fn (array $build): string => (string) $build['slug']);

        $selectedSlugs = $this->normalizeSelectedSlugs($request->query('items'));
        $selectedBuilds = collect($selectedSlugs)
            ->map(fn (string $slug): ?array => $allBuilds->get($slug))
            ->filter(fn ($build): bool => is_array($build))
            ->values()
            ->all();

        return view('compare', [
            'selectedBuilds' => $selectedBuilds,
            'selectedCompareSlugs' => array_values(array_map(
                static fn (array $build): string => (string) ($build['slug'] ?? ''),
                $selectedBuilds,
            )),
            'validCompareSlugs' => $allBuilds->keys()->values()->all(),
        ]);
    }

    protected function normalizeSelectedSlugs(mixed $value): array
    {
        $raw = trim((string) $value);

        if ($raw === '') {
            return [];
        }

        return collect(explode(',', $raw))
            ->map(static fn (string $slug): string => trim($slug))
            ->filter()
            ->unique()
            ->take(3)
            ->values()
            ->all();
    }
}
