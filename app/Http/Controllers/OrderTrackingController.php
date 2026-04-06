<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderTrackingController extends Controller
{
    public function show(Request $request): View
    {
        return view('order-tracking', [
            'order' => null,
            'prefilledNumber' => trim((string) $request->query('order', '')),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function lookup(Request $request): View|RedirectResponse
    {
        $validated = $request->validate([
            'number' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'tracking_password' => ['required', 'string', 'max:64'],
        ], [], [
            'number' => 'номер замовлення',
            'phone' => 'номер телефону',
            'tracking_password' => 'пароль',
        ]);

        $order = Order::query()
            ->with('items')
            ->where('number', trim((string) $validated['number']))
            ->first();

        if (! $order || ! $order->matchesTrackingCredentials($validated['phone'], $validated['tracking_password'])) {
            throw ValidationException::withMessages([
                'credentials' => 'Замовлення з такими даними не знайдено. Перевір номер, телефон і пароль.',
            ]);
        }

        return view('order-tracking', [
            'order' => $order,
            'prefilledNumber' => trim((string) $validated['number']),
        ]);
    }
}
