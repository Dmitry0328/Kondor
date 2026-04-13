<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Support\AccessoryCatalog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccessoryController extends Controller
{
    public function index(Request $request): View
    {
        $type = trim((string) $request->query('type'));
        $definitions = AccessoryCatalog::typeDefinitions();
        $activeType = array_key_exists($type, $definitions) ? $type : '';

        $accessories = Accessory::query()
            ->active()
            ->when($activeType !== '', fn ($query) => $query->where('type', $activeType))
            ->orderBy('type')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(fn (Accessory $accessory): array => $accessory->toStorefrontPayload())
            ->all();

        $types = collect($definitions)
            ->map(function (array $definition, string $key): array {
                return [
                    'key' => $key,
                    'label' => $definition['label'],
                    'meta' => $definition['meta'],
                    'count' => Accessory::query()->active()->where('type', $key)->count(),
                ];
            })
            ->values()
            ->all();

        return view('accessories.index', [
            'accessories' => $accessories,
            'types' => $types,
            'activeType' => $activeType,
        ]);
    }

    public function show(string $slug): View
    {
        $accessory = Accessory::query()
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        return view('accessories.show', [
            'accessory' => $accessory->toStorefrontPayload(),
        ]);
    }
}
