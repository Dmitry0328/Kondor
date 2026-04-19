<?php

namespace App\Http\Controllers;

use App\Support\OnlineVisitors;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OnlineVisitorsController extends Controller
{
    public function ping(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'context' => ['nullable', 'string', 'max:32'],
        ]);

        $context = in_array(($validated['context'] ?? 'storefront'), ['storefront', 'admin'], true)
            ? (string) ($validated['context'] ?? 'storefront')
            : 'storefront';

        return response()->json([
            'count' => OnlineVisitors::heartbeat($request, $context),
            'window_minutes' => OnlineVisitors::ACTIVE_WINDOW_MINUTES,
        ]);
    }
}
