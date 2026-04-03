<?php

namespace App\Http\Controllers;

use App\Models\Build;
use App\Models\Order;
use App\Models\SharedCart;
use App\Models\User;
use App\Notifications\NewOrderPlacedNotification;
use App\Support\BuildConfigurator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        return view('cart', [
            'sharedCartItems' => [],
            'sharedToken' => null,
            'sharedExpiresAt' => null,
        ]);
    }

    public function showShared(string $token): View
    {
        $sharedCart = SharedCart::query()
            ->where('token', $token)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        return view('cart', [
            'sharedCartItems' => $sharedCart->payload ?? [],
            'sharedToken' => $sharedCart->token,
            'sharedExpiresAt' => $sharedCart->expires_at,
        ]);
    }

    public function share(Request $request): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'items' => ['required', 'array', 'min:1'],
            'items.*.slug' => ['required', 'string', 'max:255'],
            'items.*.cartKey' => ['nullable', 'string', 'max:1024'],
            'items.*.name' => ['required', 'string', 'max:255'],
            'items.*.price' => ['required', 'integer', 'min:0'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:99'],
            'items.*.url' => ['nullable', 'string', 'max:2048'],
            'items.*.tone' => ['nullable', 'string', 'max:40'],
            'items.*.configuration' => ['nullable', 'array'],
            'items.*.configurationSummary' => ['nullable', 'array'],
            'items.*.configurationSummary.*' => ['nullable', 'string', 'max:255'],
        ])->validate();

        $items = $this->sanitizeCartItems($validated['items'], strict: true);

        $sharedCart = SharedCart::create([
            'token' => Str::lower(Str::random(32)),
            'payload' => $items,
            'expires_at' => now()->addDays(30),
        ]);

        return response()->json([
            'url' => route('cart.shared', ['token' => $sharedCart->token]),
            'expires_at' => $sharedCart->expires_at?->toIso8601String(),
        ]);
    }

    public function checkout(Request $request): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'customer_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'messenger_contact' => ['nullable', 'string', 'max:255'],
            'comment' => ['nullable', 'string', 'max:2000'],
            'payment_method' => ['required', 'in:cash_on_delivery'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.slug' => ['required', 'string', 'max:255'],
            'items.*.cartKey' => ['nullable', 'string', 'max:1024'],
            'items.*.name' => ['required', 'string', 'max:255'],
            'items.*.price' => ['required', 'integer', 'min:0'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:99'],
            'items.*.url' => ['nullable', 'string', 'max:2048'],
            'items.*.tone' => ['nullable', 'string', 'max:40'],
            'items.*.configuration' => ['nullable', 'array'],
            'items.*.configurationSummary' => ['nullable', 'array'],
            'items.*.configurationSummary.*' => ['nullable', 'string', 'max:255'],
        ])->validate();

        $items = $this->sanitizeCartItems($validated['items'], strict: true);
        $totalAmount = collect($items)->sum(fn (array $item) => $item['line_total']);

        $order = DB::transaction(function () use ($validated, $items, $totalAmount) {
            $order = Order::create([
                'status' => 'new',
                'customer_name' => $validated['customer_name'],
                'phone' => $validated['phone'],
                'messenger_contact' => $validated['messenger_contact'] ?? null,
                'email' => null,
                'comment' => $validated['comment'] ?? null,
                'payment_method' => $validated['payment_method'],
                'total_amount' => $totalAmount,
                'currency' => 'UAH',
                'meta' => [
                    'source' => 'storefront_cart',
                ],
            ]);

            $order->update([
                'number' => 'KP-' . now()->format('ymd') . '-' . str_pad((string) $order->id, 5, '0', STR_PAD_LEFT),
            ]);

            foreach ($items as $item) {
                $order->items()->create([
                    'build_slug' => $item['slug'],
                    'build_name' => $item['name'],
                    'build_url' => $item['url'],
                    'tone' => $item['tone'],
                    'unit_price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'line_total' => $item['line_total'],
                    'meta' => [
                        'cart_key' => $item['cart_key'],
                        'configuration' => $item['configuration'],
                        'configuration_summary' => $item['configuration_summary'],
                    ],
                ]);
            }

            return $order->fresh('items');
        });

        User::query()
            ->where('is_admin', true)
            ->get()
            ->each(fn (User $admin) => $admin->notify(new NewOrderPlacedNotification($order)));

        return response()->json([
            'message' => 'Замовлення оформлено. Ми зв\'яжемося з вами для підтвердження деталей.',
            'order_number' => $order->number,
        ]);
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     * @return array<int, array<string, mixed>>
     */
    protected function sanitizeCartItems(array $items, bool $strict = false): array
    {
        $normalizedItems = collect($items)
            ->map(function (array $item): array {
                $quantity = max(1, min(99, (int) ($item['quantity'] ?? 1)));

                return [
                    'slug' => (string) ($item['slug'] ?? ''),
                    'cart_key' => (string) ($item['cartKey'] ?? $item['cart_key'] ?? ''),
                    'name' => (string) ($item['name'] ?? ''),
                    'quantity' => $quantity,
                    'url' => (string) ($item['url'] ?? ''),
                    'tone' => (string) ($item['tone'] ?? 'violet'),
                    'configuration' => is_array($item['configuration'] ?? null) ? $item['configuration'] : [],
                ];
            })
            ->filter(fn (array $item): bool => $item['slug'] !== '')
            ->values();

        $builds = Build::query()
            ->whereIn('slug', $normalizedItems->pluck('slug')->unique()->all())
            ->where('is_active', true)
            ->get()
            ->keyBy('slug');

        return $normalizedItems
            ->map(function (array $item) use ($builds, $strict): ?array {
                /** @var Build|null $build */
                $build = $builds->get($item['slug']);

                if (! $build) {
                    if ($strict) {
                        throw ValidationException::withMessages([
                            'items' => ["Збірку {$item['slug']} не знайдено або вона недоступна."],
                        ]);
                    }

                    return null;
                }

                $buildPayload = $build->toStorefrontPayload();
                $resolved = BuildConfigurator::resolveSelection($buildPayload, $item['configuration']);

                if ($strict && ! ($resolved['compatibility']['is_valid'] ?? true)) {
                    throw ValidationException::withMessages([
                        'items' => [
                            'Одна з конфігурацій збірки несумісна. Перевір комплектуючі перед оформленням замовлення.',
                        ],
                    ]);
                }

                $selection = is_array($resolved['selection'] ?? null) ? $resolved['selection'] : [];
                $summary = collect($resolved['summary'] ?? [])
                    ->map(fn ($entry) => trim((string) $entry))
                    ->filter()
                    ->take(8)
                    ->values()
                    ->all();
                $price = max(0, (int) ($resolved['total_price'] ?? ($buildPayload['price_raw'] ?? 0)));
                $quantity = $item['quantity'];

                return [
                    'slug' => (string) $build->slug,
                    'cart_key' => $item['cart_key'] !== '' ? $item['cart_key'] : BuildConfigurator::cartKey((string) $build->slug, $selection),
                    'name' => (string) $build->name,
                    'price' => $price,
                    'quantity' => $quantity,
                    'line_total' => $price * $quantity,
                    'url' => route('product.show', ['slug' => $build->slug]),
                    'tone' => (string) ($build->tone ?? 'violet'),
                    'configuration' => $selection,
                    'configuration_summary' => $summary,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }
}
