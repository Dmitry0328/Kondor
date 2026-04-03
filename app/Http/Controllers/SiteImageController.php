<?php

namespace App\Http\Controllers;

use App\Models\SiteImage;
use App\Support\SiteImages;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteImageController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $validated = $request->validate([
            'key' => ['required', 'string', 'max:160', 'regex:/^[A-Za-z0-9._-]+$/'],
            'image' => ['required', 'image', 'max:8192'],
        ]);

        $existing = SiteImage::query()->firstWhere('key', $validated['key']);
        $path = $request->file('image')->store('site-images', 'public');

        if ($existing?->path) {
            Storage::disk($existing->disk ?: 'public')->delete($existing->path);
        }

        $siteImage = SiteImage::query()->updateOrCreate(
            ['key' => $validated['key']],
            [
                'disk' => 'public',
                'path' => $path,
                'updated_by' => $request->user()->id,
            ],
        );

        SiteImages::flush();

        return response()->json([
            'key' => $siteImage->key,
            'url' => $siteImage->url,
        ]);
    }
}
