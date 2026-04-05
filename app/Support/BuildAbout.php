<?php

namespace App\Support;

class BuildAbout
{
    public static function resolve(array $build): array
    {
        $about = is_array($build['about'] ?? null) ? $build['about'] : [];
        $fallback = static::fallback($build);

        return [
            'intro' => static::stringList($about['intro'] ?? null, $fallback['intro']),
            'notes' => static::stringList($about['notes'] ?? null, $fallback['notes']),
            'setup_title' => static::stringValue($about['setup_title'] ?? null) ?? $fallback['setup_title'],
            'setup_items' => static::stringList($about['setup_items'] ?? null, $fallback['setup_items']),
            'delivery_title' => static::stringValue($about['delivery_title'] ?? null) ?? $fallback['delivery_title'],
            'delivery_items' => static::stringList($about['delivery_items'] ?? null, $fallback['delivery_items']),
            'delivery_steps' => static::stringList($about['delivery_steps'] ?? null, $fallback['delivery_steps']),
            'warranty_title' => static::stringValue($about['warranty_title'] ?? null) ?? $fallback['warranty_title'],
            'warranty_items' => static::stringList($about['warranty_items'] ?? null, $fallback['warranty_items']),
        ];
    }

    protected static function fallback(array $build): array
    {
        $name = static::stringValue($build['name'] ?? null) ?? 'Ігровий ПК';
        $gpu = static::stringValue($build['gpu'] ?? null) ?? 'Відеокарта';
        $cpu = static::stringValue($build['cpu'] ?? null) ?? 'процесор';
        $ram = static::stringValue($build['ram'] ?? null) ?? 'оперативна пам\'ять';
        $storage = static::stringValue($build['storage'] ?? null) ?? 'накопичувач';
        $fpsScore = max(0, (int) ($build['fps_score'] ?? 0));

        $performanceLine = match (true) {
            $fpsScore >= 160 => 'забезпечують високий FPS у сучасних іграх',
            $fpsScore >= 120 => 'забезпечують впевнений FPS у сучасних іграх',
            default => 'дають збалансовану продуктивність у сучасних іграх',
        };

        $benchmarkResolution = $fpsScore >= 140
            ? '2K роздільній здатності (2560×1440 / 1440p)'
            : 'Full HD роздільній здатності (1920×1080 / 1080p)';

        return [
            'intro' => [
                $name . ' - потужна та стильна ігрова збірка.',
                $gpu . ' і ' . $cpu . ' ' . $performanceLine . ', а ' . $ram . ' та ' . $storage . ' гарантують швидку та стабільну роботу системи.',
            ],
            'notes' => [
                'Можливий у білому та чорному виконанні',
                'Тести було записано в ' . $benchmarkResolution,
                'Важливо: всі деталі нові',
                '* ГАРАНТІЯ на комп\'ютер - 24/36 місяців *',
            ],
            'setup_title' => 'На комп\'ютері буде зроблено',
            'setup_items' => [
                'Встановлення Windows 11 Pro та повний набір драйверів',
                'Ретельне тестування ПК перед відправкою',
                'ПК буде повністю готовий до використання',
            ],
            'delivery_title' => 'Оплата та Доставка',
            'delivery_items' => [
                'Самовивіз з нашого офісу (м. Київ, вул. Дмитра Багалія 4)',
                'Доставка Новою поштою',
            ],
            'delivery_steps' => [
                'Накладним платежем з оплатою на пошті',
                'З повною передоплатою звичайною доставкою',
            ],
            'warranty_title' => 'Умови гарантії та повернення',
            'warranty_items' => [
                '14 днів на перевірку та повернення у разі відсутності пошкоджень та змін в конфігурації системи',
                'Безкоштовна online-консультація протягом всього часу після купівлі збірки',
                'Безкоштовний ремонт протягом всього гарантійного терміну, при дотриманні гарантійних умов',
            ],
        ];
    }

    protected static function stringList(mixed $value, array $fallback): array
    {
        if (! is_array($value)) {
            return $fallback;
        }

        $items = array_values(array_filter(array_map(
            static fn (mixed $item): string => trim((string) $item),
            $value,
        )));

        return $items !== [] ? $items : $fallback;
    }

    protected static function stringValue(mixed $value): ?string
    {
        $value = trim((string) $value);

        return $value !== '' ? $value : null;
    }
}
