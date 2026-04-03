<?php

namespace App\Http\Controllers;

use App\Filament\Resources\Builds\BuildResource;
use App\Models\SharedBuildLink;
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
                'build_url' => route('product.show', ['slug' => $build['slug']]),
            ],
            'expires_at' => now()->addDays(30),
        ]);

        return response()->json([
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

        if ($sharedBuildLink) {
            $payload = is_array($sharedBuildLink->payload) ? $sharedBuildLink->payload : [];
            $sharedBuildSelection = is_array($payload['selection'] ?? null) ? $payload['selection'] : [];
        }

        return view('product', [
            'build' => $build,
            'sharedBuildLink' => $sharedBuildLink,
            'sharedBuildSelection' => $sharedBuildSelection,
            'isPreview' => $isPreview,
            'previewToken' => $previewToken,
        ]);
    }

    protected function resolveSelection(array $build, array $selection): array
    {
        $productConfigurator = BuildConfigurator::storefrontPayload($build);

        if ((bool) ($productConfigurator['enabled'] ?? false)) {
            return BuildConfigurator::resolvePayloadSelection($productConfigurator, $selection);
        }

        return [
            'enabled' => false,
            'selection' => [],
            'summary' => [],
            'additional_price' => 0,
            'total_price' => (int) ($build['price_raw'] ?? 0),
            'compatibility' => [
                'is_valid' => true,
                'messages' => [],
            ],
        ];
    }
}
