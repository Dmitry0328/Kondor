<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class SiteAdminNotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        abort_unless($user?->is_admin, 403);

        $notifications = $user->unreadNotifications()
            ->latest()
            ->limit(8)
            ->get()
            ->map(function (DatabaseNotification $notification): array {
                $actions = collect(data_get($notification->data, 'actions', []));
                $firstAction = $actions->first();

                return [
                    'id' => $notification->id,
                    'title' => (string) (data_get($notification->data, 'title') ?: 'Нове сповіщення'),
                    'body' => trim(strip_tags((string) data_get($notification->data, 'body', ''))),
                    'url' => data_get($firstAction, 'url') ?: url('/admin'),
                    'created_at' => $notification->created_at?->format('d.m.Y H:i'),
                ];
            })
            ->values();

        return response()->json([
            'notifications' => $notifications,
        ]);
    }
}
