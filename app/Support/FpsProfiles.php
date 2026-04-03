<?php

namespace App\Support;

class FpsProfiles
{
    public static function stateKey(string $game, string $display, string $preset): string
    {
        return trim($game) . '|' . trim($display) . '|' . trim($preset);
    }

    public static function normalize(array $profiles, array $catalog): array
    {
        $validGames = array_flip(array_column($catalog['games'] ?? [], 'id'));
        $validDisplays = array_flip(array_column($catalog['displays'] ?? [], 'id'));
        $validPresets = array_flip(array_column($catalog['presets'] ?? [], 'id'));

        $normalized = [];

        foreach ($profiles as $profile) {
            if (! is_array($profile)) {
                continue;
            }

            $game = trim((string) ($profile['game'] ?? ''));
            $display = trim((string) ($profile['display'] ?? ''));
            $preset = trim((string) ($profile['preset'] ?? ''));
            $fps = (int) round((float) ($profile['fps'] ?? 0));

            if (
                $game === '' ||
                $display === '' ||
                $preset === '' ||
                $fps < 1 ||
                ! isset($validGames[$game]) ||
                ! isset($validDisplays[$display]) ||
                ! isset($validPresets[$preset])
            ) {
                continue;
            }

            $normalized[static::stateKey($game, $display, $preset)] = [
                'game' => $game,
                'display' => $display,
                'preset' => $preset,
                'fps' => $fps,
            ];
        }

        return array_values($normalized);
    }

    public static function makeLookup(array $profiles): array
    {
        $lookup = [];

        foreach ($profiles as $profile) {
            if (! is_array($profile)) {
                continue;
            }

            $game = trim((string) ($profile['game'] ?? ''));
            $display = trim((string) ($profile['display'] ?? ''));
            $preset = trim((string) ($profile['preset'] ?? ''));
            $fps = (int) round((float) ($profile['fps'] ?? 0));

            if ($game === '' || $display === '' || $preset === '' || $fps < 1) {
                continue;
            }

            $lookup[static::stateKey($game, $display, $preset)] = $fps;
        }

        return $lookup;
    }

    public static function ensureProfiles(array $profiles, array $catalog, int $fallbackFps = 90): array
    {
        $normalized = static::normalize($profiles, $catalog);

        if ($normalized !== []) {
            return $normalized;
        }

        $fallback = static::makeFallbackProfile($catalog, $fallbackFps);

        return $fallback ? [$fallback] : [];
    }

    public static function defaultState(array $catalog, array $profiles): array
    {
        [$defaultGame, $defaultDisplay, $defaultPreset] = static::defaultsFromCatalog($catalog);
        $lookup = static::makeLookup($profiles);

        if (
            $defaultGame !== '' &&
            $defaultDisplay !== '' &&
            $defaultPreset !== '' &&
            isset($lookup[static::stateKey($defaultGame, $defaultDisplay, $defaultPreset)])
        ) {
            return [
                'game' => $defaultGame,
                'display' => $defaultDisplay,
                'preset' => $defaultPreset,
            ];
        }

        $first = $profiles[0] ?? null;

        if (is_array($first)) {
            return [
                'game' => (string) ($first['game'] ?? $defaultGame),
                'display' => (string) ($first['display'] ?? $defaultDisplay),
                'preset' => (string) ($first['preset'] ?? $defaultPreset),
            ];
        }

        return [
            'game' => $defaultGame,
            'display' => $defaultDisplay,
            'preset' => $defaultPreset,
        ];
    }

    public static function resolve(
        array $lookup,
        array $profiles,
        string $game,
        string $display,
        string $preset,
        int $fallbackFps = 0,
    ): int {
        $stateKey = static::stateKey($game, $display, $preset);

        if (isset($lookup[$stateKey])) {
            return max(1, (int) $lookup[$stateKey]);
        }

        return max(0, $fallbackFps);
    }

    protected static function makeFallbackProfile(array $catalog, int $fps): ?array
    {
        [$game, $display, $preset] = static::defaultsFromCatalog($catalog);

        if ($game === '' || $display === '' || $preset === '') {
            return null;
        }

        return [
            'game' => $game,
            'display' => $display,
            'preset' => $preset,
            'fps' => max(1, $fps),
        ];
    }

    protected static function defaultsFromCatalog(array $catalog): array
    {
        $games = array_column($catalog['games'] ?? [], 'id');
        $displays = array_column($catalog['displays'] ?? [], 'id');
        $presets = array_column($catalog['presets'] ?? [], 'id');

        $defaultGame = (string) ($catalog['defaults']['game'] ?? ($games[0] ?? ''));
        $defaultDisplay = (string) ($catalog['defaults']['display'] ?? ($displays[0] ?? ''));
        $defaultPreset = (string) ($catalog['defaults']['preset'] ?? ($presets[0] ?? ''));

        if (! in_array($defaultGame, $games, true)) {
            $defaultGame = (string) ($games[0] ?? '');
        }

        if (! in_array($defaultDisplay, $displays, true)) {
            $defaultDisplay = (string) ($displays[0] ?? '');
        }

        if (! in_array($defaultPreset, $presets, true)) {
            $defaultPreset = (string) ($presets[0] ?? '');
        }

        return [$defaultGame, $defaultDisplay, $defaultPreset];
    }
}
