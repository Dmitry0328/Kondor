<?php

namespace App\Support;

class YoutubeVideo
{
    public static function sanitize(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value !== '' ? $value : null;
    }

    public static function extractId(?string $value): ?string
    {
        $value = static::sanitize($value);

        if ($value === null) {
            return null;
        }

        if (! filter_var($value, FILTER_VALIDATE_URL)) {
            return null;
        }

        $parts = parse_url($value);
        $host = strtolower((string) ($parts['host'] ?? ''));
        $path = trim((string) ($parts['path'] ?? ''), '/');
        $query = [];

        parse_str((string) ($parts['query'] ?? ''), $query);

        $candidate = null;

        if (in_array($host, ['youtu.be', 'www.youtu.be'], true)) {
            $segments = array_values(array_filter(explode('/', $path)));
            $candidate = $segments[0] ?? null;
        } elseif (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com'], true)) {
            $segments = array_values(array_filter(explode('/', $path)));

            if (($segments[0] ?? null) === 'watch') {
                $candidate = $query['v'] ?? null;
            } elseif (in_array($segments[0] ?? null, ['embed', 'shorts', 'live'], true)) {
                $candidate = $segments[1] ?? null;
            }
        }

        $candidate = trim((string) $candidate);

        if ($candidate === '' || ! preg_match('/^[A-Za-z0-9_-]{11}$/', $candidate)) {
            return null;
        }

        return $candidate;
    }

    public static function embedUrl(?string $value): ?string
    {
        $videoId = static::extractId($value);

        if ($videoId === null) {
            return null;
        }

        return 'https://www.youtube.com/embed/' . $videoId . '?rel=0';
    }
}
