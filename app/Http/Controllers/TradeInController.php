<?php

namespace App\Http\Controllers;

use App\Models\Build;
use App\Models\SharedBuildLink;
use App\Models\TradeInRequest;
use App\Models\User;
use App\Notifications\NewTradeInRequestNotification;
use App\Support\BuildConfigurator;
use App\Support\StorefrontBuilds;
use App\Support\TradeInPhotoStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TradeInController extends Controller
{
    public function show(Request $request): View
    {
        $storefrontBuilds = StorefrontBuilds::all();
        $sharedBuildLink = filled($request->query('shared_build'))
            ? SharedBuildLink::query()->active()->where('token', (string) $request->query('shared_build'))->first()
            : null;
        $selectedBuild = $sharedBuildLink
            ? StorefrontBuilds::findBySlug((string) $sharedBuildLink->build_slug)
            : ($request->query('build')
            ? StorefrontBuilds::findBySlug((string) $request->query('build'))
            : null);

        return view('trade-in', [
            'storefrontBuilds' => $storefrontBuilds,
            'headerBuilds' => array_slice($storefrontBuilds, 0, 4),
            'selectedBuild' => $selectedBuild,
            'selectedBuildSlug' => (string) old('build_slug', $selectedBuild['slug'] ?? $sharedBuildLink?->build_slug ?? ''),
            'selectedSharedBuildToken' => (string) old('shared_build_token', $sharedBuildLink?->token ?? ''),
            'tradeInBuildSnapshotPreview' => $this->makeBuildSnapshot(
                $selectedBuild ? Build::query()->where('slug', (string) ($selectedBuild['slug'] ?? ''))->first() : null,
                $sharedBuildLink,
            ),
            'tradeInSuccess' => session('tradeInSuccess'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = Validator::make($request->all(), [
            'build_slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::exists('builds', 'slug')->where(fn ($query) => $query->where('is_active', true)),
            ],
            'customer_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'messenger_contact' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:4000'],
            'shared_build_token' => ['nullable', 'string', 'size:32'],
            'photos' => ['nullable', 'array', 'max:6'],
            'photos.*' => ['nullable', 'file', 'max:8192'],
        ], [
            'photos.max' => 'Можна додати не більше 6 фото.',
            'photos.*.max' => 'Кожне фото має бути до 8MB.',
        ])->validate();

        $sharedBuildLink = filled($validated['shared_build_token'] ?? null)
            ? SharedBuildLink::query()->active()->where('token', (string) $validated['shared_build_token'])->first()
            : null;
        $build = filled($validated['build_slug'] ?? null)
            ? Build::query()
                ->where('slug', (string) $validated['build_slug'])
                ->where('is_active', true)
                ->first()
            : ($sharedBuildLink
                ? Build::query()
                    ->where('slug', (string) $sharedBuildLink->build_slug)
                    ->where('is_active', true)
                    ->first()
                : null);

        if ($sharedBuildLink && $build && $sharedBuildLink->build_slug !== $build->slug) {
            $sharedBuildLink = null;
        }

        $photoPaths = TradeInPhotoStorage::storeMany($request->file('photos', []));
        $buildSnapshot = $this->makeBuildSnapshot($build, $sharedBuildLink);

        try {
            $tradeInRequest = DB::transaction(function () use ($validated, $build, $photoPaths, $buildSnapshot): TradeInRequest {
                return TradeInRequest::create([
                    'build_id' => $build?->getKey(),
                    'build_slug' => $build?->slug,
                    'build_name' => $build?->name,
                    'status' => 'new',
                    'customer_name' => (string) $validated['customer_name'],
                    'phone' => (string) $validated['phone'],
                    'messenger_contact' => filled($validated['messenger_contact'] ?? null)
                        ? (string) $validated['messenger_contact']
                        : null,
                    'description' => trim((string) $validated['description']),
                    'photo_paths' => $photoPaths !== [] ? $photoPaths : null,
                    'build_snapshot' => $buildSnapshot,
                ]);
            });
        } catch (\Throwable $exception) {
            if ($photoPaths !== []) {
                Storage::disk('public')->delete($photoPaths);
            }

            throw $exception;
        }

        User::query()
            ->where('is_admin', true)
            ->get()
            ->each(fn (User $admin) => $admin->notify(new NewTradeInRequestNotification($tradeInRequest)));

        $routeParameters = [];

        if ($tradeInRequest->build_slug) {
            $routeParameters['build'] = $tradeInRequest->build_slug;
        }

        if (filled($validated['shared_build_token'] ?? null) && $buildSnapshot !== null) {
            $routeParameters['shared_build'] = (string) $validated['shared_build_token'];
        }

        return redirect()
            ->route('trade-in', $routeParameters)
            ->with('tradeInSuccess', [
                'message' => 'Заявку на трейд-ін відправлено. Ми переглянемо опис і фото та зв’яжемося з вами.',
                'request_id' => $tradeInRequest->getKey(),
            ]);
    }

    protected function makeBuildSnapshot(?Build $build, ?SharedBuildLink $sharedBuildLink): ?array
    {
        if (! $build && ! $sharedBuildLink) {
            return null;
        }

        $baseUrl = $build?->slug
            ? route('product.show', ['slug' => $build->slug])
            : null;

        if ($sharedBuildLink) {
            $payload = is_array($sharedBuildLink->payload) ? $sharedBuildLink->payload : [];
            $summary = array_values(array_filter(array_map(
                static fn ($line): string => trim((string) $line),
                (array) ($payload['summary'] ?? []),
            )));

            if ($summary === [] && $build) {
                $summary = $this->defaultBuildSummary($build);
            }

            return [
                'selection' => is_array($payload['selection'] ?? null) ? $payload['selection'] : [],
                'summary' => $summary,
                'additional_price' => max(0, (int) ($payload['additional_price'] ?? 0)),
                'total_price' => max(0, (int) ($payload['total_price'] ?? ($build?->price ?? 0))),
                'compatibility' => is_array($payload['compatibility'] ?? null) ? $payload['compatibility'] : ['is_valid' => true, 'messages' => []],
                'shared_url' => $sharedBuildLink->shared_url,
                'build_url' => $baseUrl,
                'is_customized' => $summary !== [] || max(0, (int) ($payload['additional_price'] ?? 0)) > 0,
            ];
        }

        if (! $build) {
            return null;
        }

        return [
            'selection' => [],
            'summary' => $this->defaultBuildSummary($build),
            'additional_price' => 0,
            'total_price' => (int) $build->price,
            'compatibility' => ['is_valid' => true, 'messages' => []],
            'build_url' => $baseUrl,
            'is_customized' => false,
        ];
    }

    protected function defaultBuildSummary(Build $build): array
    {
        $payload = $build->toStorefrontPayload();
        $configuratorSummary = BuildConfigurator::resolveSelection($payload)['summary'] ?? [];
        $configuratorSummary = array_values(array_filter(array_map(
            static fn ($line): string => trim((string) $line),
            (array) $configuratorSummary,
        )));

        if ($configuratorSummary !== []) {
            return $configuratorSummary;
        }

        return array_values(array_filter([
            filled($payload['gpu'] ?? null) ? 'Відеокарта: ' . (string) $payload['gpu'] : null,
            filled($payload['cpu'] ?? null) ? 'Процесор: ' . (string) $payload['cpu'] : null,
            filled($payload['ram'] ?? null) ? "Оперативна пам'ять: " . (string) $payload['ram'] : null,
            filled($payload['storage'] ?? null) ? 'Накопичувач: ' . (string) $payload['storage'] : null,
        ]));
    }
}
