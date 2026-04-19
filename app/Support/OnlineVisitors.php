<?php

namespace App\Support;

use App\Models\OnlineVisitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class OnlineVisitors
{
    public const ACTIVE_WINDOW_MINUTES = 5;

    public static function heartbeat(Request $request, string $context = 'storefront'): int
    {
        if (! Schema::hasTable('online_visitors')) {
            return 0;
        }

        $now = now();
        $sessionId = $request->hasSession() ? $request->session()->getId() : null;
        $userId = $request->user()?->getAuthIdentifier();
        $userAgent = Str::limit((string) $request->userAgent(), 1024, '');
        $fingerprint = static::fingerprint($sessionId, $userId, (string) $request->ip(), $userAgent);

        OnlineVisitor::query()->updateOrCreate(
            ['fingerprint' => $fingerprint],
            [
                'session_id' => $sessionId ?: null,
                'user_id' => $userId,
                'ip_address' => (string) $request->ip(),
                'user_agent' => $userAgent,
                'last_seen_at' => $now,
                'last_path' => Str::limit('/' . ltrim($request->path(), '/'), 512, ''),
                'context' => $context,
            ],
        );

        static::pruneIfNeeded($now);

        return static::activeCount($now);
    }

    public static function activeCount($now = null): int
    {
        if (! Schema::hasTable('online_visitors')) {
            return 0;
        }

        $now ??= now();

        return OnlineVisitor::query()
            ->where('last_seen_at', '>=', $now->copy()->subMinutes(static::ACTIVE_WINDOW_MINUTES))
            ->count();
    }

    protected static function fingerprint(?string $sessionId, mixed $userId, string $ipAddress, string $userAgent): string
    {
        return hash('sha256', implode('|', [
            $sessionId ?: 'no-session',
            $userId ?: 'guest',
            $ipAddress,
            $userAgent,
        ]));
    }

    protected static function pruneIfNeeded($now): void
    {
        try {
            if (random_int(1, 150) !== 1) {
                return;
            }
        } catch (\Throwable) {
            return;
        }

        OnlineVisitor::query()
            ->where('last_seen_at', '<', $now->copy()->subDay())
            ->delete();
    }
}
