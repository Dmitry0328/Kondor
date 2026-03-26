<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\SharedCart;
use App\Models\User;
use App\Notifications\NewOrderPlacedNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
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
            'items.*.name' => ['required', 'string', 'max:255'],
            'items.*.price' => ['required', 'integer', 'min:0'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:99'],
            'items.*.url' => ['nullable', 'string', 'max:2048'],
            'items.*.tone' => ['nullable', 'string', 'max:40'],
        ])->validate();

        $items = $this->sanitizeCartItems($validated['items']);

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
            'items.*.name' => ['required', 'string', 'max:255'],
            'items.*.price' => ['required', 'integer', 'min:0'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:99'],
            'items.*.url' => ['nullable', 'string', 'max:2048'],
            'items.*.tone' => ['nullable', 'string', 'max:40'],
        ])->validate();

        $items = $this->sanitizeCartItems($validated['items']);
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
    protected function sanitizeCartItems(array $items): array
    {
        return collect($items)
            ->map(function (array $item): array {
                $price = max(0, (int) ($item['price'] ?? 0));
                $quantity = max(1, min(99, (int) ($item['quantity'] ?? 1)));

                return [
                    'slug' => (string) ($item['slug'] ?? ''),
                    'name' => (string) ($item['name'] ?? ''),
                    'price' => $price,
                    'quantity' => $quantity,
                    'line_total' => $price * $quantity,
                    'url' => (string) ($item['url'] ?? ''),
                    'tone' => (string) ($item['tone'] ?? 'violet'),
                ];
            })
            ->filter(fn (array $item): bool => $item['slug'] !== '' && $item['name'] !== '')
            ->values()
            ->all();
    }
}
