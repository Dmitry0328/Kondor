<?php

namespace App\Http\Controllers;

use App\Filament\Resources\Builds\BuildResource;
use App\Models\Accessory;
use App\Models\SharedBuildLink;
use App\Support\AccessoryCatalog;
use App\Support\BuildConfigurator;
use App\Support\BuildPreview;
use Illuminate\Http\RedirectResponse;
use App\Support\StorefrontBuilds;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(string $slug): View
    {
        $build = StorefrontBuilds::findBySlug($slug);

        abort_unless($build, 404);

        return $this->renderProductPage($build);
    }

    public function showShared(string $token): View
    {
        $sharedBuildLink = SharedBuildLink::query()
            ->active()
            ->where('token', $token)
            ->firstOrFail();

        $build = StorefrontBuilds::findBySlug($sharedBuildLink->build_slug);

        abort_unless($build, 404);

        return $this->renderProductPage($build, $sharedBuildLink);
    }

    public function showPreview(string $token): View
    {
        $build = BuildPreview::find($token);

        abort_unless($build, 404);

        return $this->renderProductPage($build, isPreview: true, previewToken: $token);
    }

    public function persistPreview(Request $request, string $token): RedirectResponse
    {
        abort_unless(Auth::user()?->is_admin, 403);

        $validated = Validator::make($request->all(), [
            'mode' => ['required', 'in:publish,draft'],
        ])->validate();

        try {
            $build = BuildPreview::persist($token, $validated['mode'] === 'publish');
        } catch (ValidationException $exception) {
            return redirect()
                ->route('product.preview', ['token' => $token])
                ->with('previewPersistError', $exception->validator->errors()->first());
        }

        return redirect()
            ->route('product.preview', ['token' => $token])
            ->with('previewPersistState', [
                'mode' => $validated['mode'],
                'message' => $validated['mode'] === 'publish'
                    ? 'Збірку опубліковано. Вона вже доступна на сайті.'
                    : 'Збірку збережено як чернетку. На сайті її не видно покупцям.',
                'edit_url' => BuildResource::getUrl('edit', ['record' => $build->getKey()], isAbsolute: false),
            ]);
    }

    public function share(Request $request, string $slug): JsonResponse
    {
        $build = StorefrontBuilds::findBySlug($slug);

        abort_unless($build, 404);

        $validated = Validator::make($request->all(), [
            'selection' => ['nullable', 'array'],
            'selection.*' => ['nullable', 'string', 'max:255'],
        ])->validate();

        $resolved = $this->resolveSelection($build, $validated['selection'] ?? []);

        $sharedBuildLink = SharedBuildLink::create([
            'token' => Str::lower(Str::random(32)),
            'build_slug' => (string) ($build['slug'] ?? ''),
            'build_name' => (string) ($build['name'] ?? ''),
            'payload' => [
                'selection' => $resolved['selection'] ?? [],
                'summary' => $resolved['summary'] ?? [],
                'additional_price' => (int) ($resolved['additional_price'] ?? 0),
                'total_price' => (int) ($resolved['total_price'] ?? ((int) ($build['price_raw'] ?? 0))),
                'compatibility' => $resolved['compatibility'] ?? ['is_valid' => true, 'messages' => []],
                'accessories' => $resolved['accessories'] ?? [],
                'build_url' => route('product.show', ['slug' => $build['slug']]),
            ],
            'expires_at' => now()->addDays(30),
        ]);

        return response()->json([
            'token' => $sharedBuildLink->token,
            'url' => $sharedBuildLink->shared_url,
            'expires_at' => $sharedBuildLink->expires_at?->toIso8601String(),
        ]);
    }

    protected function renderProductPage(
        array $build,
        ?SharedBuildLink $sharedBuildLink = null,
        bool $isPreview = false,
        ?string $previewToken = null,
    ): View
    {
        $sharedBuildSelection = [];
        $sharedBuildPayload = [];

        if ($sharedBuildLink) {
            $sharedBuildPayload = is_array($sharedBuildLink->payload) ? $sharedBuildLink->payload : [];
            $sharedBuildSelection = is_array($sharedBuildPayload['selection'] ?? null) ? $sharedBuildPayload['selection'] : [];
        }

        return view('product', [
            'build' => $build,
            'accessoryGroups' => AccessoryCatalog::storefrontGroups(),
            'sharedBuildLink' => $sharedBuildLink,
            'sharedBuildPayload' => $sharedBuildPayload,
            'sharedBuildSelection' => $sharedBuildSelection,
            'isPreview' => $isPreview,
            'previewToken' => $previewToken,
        ]);
    }

    protected function resolveSelection(array $build, array $selection): array
    {
        [$configuratorSelection, $accessorySelection] = $this->splitSelection($selection);
        $productConfigurator = BuildConfigurator::storefrontPayload($build);

        $resolved = (bool) ($productConfigurator['enabled'] ?? false)
            ? BuildConfigurator::resolvePayloadSelection($productConfigurator, $configuratorSelection)
            : [
            'enabled' => false,
            'selection' => [],
            'summary' => [],
            'additional_price' => 0,
            'total_price' => (int) ($build['price_raw'] ?? 0),
            'compatibility' => [
                'is_valid' => true,
                'messages' => [],
                'invalid_slots' => [],
                'slot_messages' => [],
            ],
        ];

        $resolvedAccessories = $this->resolveAccessorySelection($accessorySelection);
        $additionalPrice = (int) ($resolved['additional_price'] ?? 0) + (int) ($resolvedAccessories['additional_price'] ?? 0);

        return [
            ...$resolved,
            'selection' => [
                ...(is_array($resolved['selection'] ?? null) ? $resolved['selection'] : []),
                ...(is_array($resolvedAccessories['selection'] ?? null) ? $resolvedAccessories['selection'] : []),
            ],
            'summary' => [
                ...(is_array($resolved['summary'] ?? null) ? $resolved['summary'] : []),
                ...(is_array($resolvedAccessories['summary'] ?? null) ? $resolvedAccessories['summary'] : []),
            ],
            'additional_price' => $additionalPrice,
            'total_price' => (int) ($build['price_raw'] ?? 0) + $additionalPrice,
            'accessories' => $resolvedAccessories['items'] ?? [],
        ];
    }

    protected function splitSelection(array $selection): array
    {
        $configuratorSelection = [];
        $accessorySelection = [];

        foreach ($selection as $key => $value) {
            $key = trim((string) $key);
            $value = trim((string) $value);

            if ($key === '' || $value === '') {
                continue;
            }

            if (Str::startsWith($key, 'accessory_')) {
                $accessorySelection[$key] = $value;

                continue;
            }

            $configuratorSelection[$key] = $value;
        }

        return [$configuratorSelection, $accessorySelection];
    }

    protected function resolveAccessorySelection(array $selection): array
    {
        $definitions = AccessoryCatalog::typeDefinitions();
        $requestedTypes = [];

        foreach ($selection as $key => $slug) {
            $type = Str::after((string) $key, 'accessory_');

            if ($type === '' || ! array_key_exists($type, $definitions)) {
                continue;
            }

            $requestedTypes[$type] = trim((string) $slug);
        }

        if ($requestedTypes === []) {
            return [
                'selection' => [],
                'summary' => [],
                'additional_price' => 0,
                'items' => [],
            ];
        }

        $accessories = Accessory::query()
            ->active()
            ->whereIn('slug', array_values($requestedTypes))
            ->get()
            ->keyBy('slug');

        $normalizedSelection = [];
        $summary = [];
        $items = [];
        $additionalPrice = 0;

        foreach (array_keys($definitions) as $type) {
            $slug = $requestedTypes[$type] ?? '';

            if ($slug === '') {
                continue;
            }

            $accessory = $accessories->get($slug);

            if (! $accessory instanceof Accessory) {
                continue;
            }

            $price = max(0, (int) ($accessory->price ?? 0));
            $label = AccessoryCatalog::typeLabel($type);
            $normalizedSelection['accessory_' . $type] = (string) $accessory->slug;
            $summary[] = $label . ': ' . (string) $accessory->name . ($price > 0 ? ' +' . number_format($price, 0, '.', ' ') . ' грн' : '');
            $items[] = [
                'type' => $type,
                'label' => $label,
                'slug' => (string) $accessory->slug,
                'name' => (string) $accessory->name,
                'price' => $price,
            ];
            $additionalPrice += $price;
        }

        return [
            'selection' => $normalizedSelection,
            'summary' => $summary,
            'additional_price' => $additionalPrice,
            'items' => $items,
        ];
    }
}
