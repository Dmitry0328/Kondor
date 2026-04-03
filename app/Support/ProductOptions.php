<?php

namespace App\Support;

class ProductOptions
{
    public static function fallbackForBuild(array $build): array
    {
        $gpu = (string) ($build['gpu'] ?? 'Поточна відеокарта');
        $cpu = (string) ($build['cpu'] ?? 'Поточний процесор');
        $ram = (string) ($build['ram'] ?? 'Поточна пам\'ять');
        $storage = (string) ($build['storage'] ?? 'Поточний накопичувач');
        $slug = (string) ($build['slug'] ?? '');

        $gpuUpgrade = str_contains(mb_strtolower($gpu), '4070')
            ? 'Nvidia RTX 4070 Ti Super'
            : (str_contains(mb_strtolower($gpu), '5070') ? 'Nvidia RTX 5070 Ti' : 'Nvidia RTX 4080 Super');

        $cpuUpgrade = str_contains(mb_strtolower($cpu), '7800x3d')
            ? 'AMD Ryzen 7 9800X3D'
            : (str_contains(mb_strtolower($cpu), '9700x') ? 'AMD Ryzen 9 9900X' : 'Intel Core i7 / Ryzen 7 next tier');

        $boardBase = match ($slug) {
            'nova', 'crystal', 'storm', 'pulse', 'atlas' => 'B650 Wi-Fi',
            'titan' => 'Z790 Creator',
            default => 'B550 / B660 class motherboard',
        };

        $powerUpgrade = match ($slug) {
            'atlas' => '1200W Gold full-modular',
            'titan', 'crystal' => '1000W Gold full-modular',
            default => '850W Gold full-modular',
        };

        $caseLabel = match ($slug) {
            'nova' => 'Showcase glass case',
            'crystal' => 'Creator airflow case',
            'storm' => 'Black airflow tower',
            default => 'Gaming showcase case',
        };

        $groups = [
            [
                'id' => 'modding',
                'title' => 'Моддинг',
                'description' => null,
                'options' => [
                    ['key' => 'default', 'label' => 'Стандартне виконання', 'description' => 'Базовий cable management та заводський профіль ARGB.', 'price' => 0, 'selected' => false],
                    ['key' => 'showcase-rgb', 'label' => 'Showcase RGB + cable kit', 'description' => 'Покращена укладка кабелів, акцентні combs та сценічне підсвічування.', 'price' => 1800, 'selected' => true],
                    ['key' => 'premium-mod', 'label' => 'Premium mod package', 'description' => 'Декоративні вставки, додаткові light-bars та custom routing.', 'price' => 3600, 'selected' => false],
                ],
            ],
            [
                'id' => 'gpu',
                'title' => 'Заміна відеокарти',
                'description' => null,
                'options' => [
                    ['key' => 'base', 'label' => $gpu, 'description' => 'Поточна конфігурація збірки.', 'price' => 0, 'selected' => true],
                    ['key' => 'upgrade', 'label' => $gpuUpgrade, 'description' => 'Апгрейд на клас вище для запасу по 2K / 4K.', 'price' => 5600, 'selected' => false],
                    ['key' => 'white-oc', 'label' => 'White / OC edition під замовлення', 'description' => 'Підбір версії під стиль корпусу та охолодження.', 'price' => 7900, 'selected' => false],
                ],
            ],
            [
                'id' => 'cpu',
                'title' => 'Заміна процесора',
                'description' => null,
                'options' => [
                    ['key' => 'base', 'label' => $cpu, 'description' => 'Поточна продуктивність збірки.', 'price' => 0, 'selected' => true],
                    ['key' => 'upgrade', 'label' => $cpuUpgrade, 'description' => 'Більше запасу для high-refresh gaming та фонового стріму.', 'price' => 4200, 'selected' => false],
                    ['key' => 'creator', 'label' => 'Флагманська версія під задачі creator', 'description' => 'Максимум продуктивності для монтажу й рендеру.', 'price' => 7900, 'selected' => false],
                ],
            ],
            [
                'id' => 'ram',
                'title' => 'Зміна ОЗП',
                'description' => null,
                'options' => [
                    ['key' => 'base', 'label' => $ram, 'description' => 'Стандартна комплектація.', 'price' => 0, 'selected' => true],
                    ['key' => '48gb', 'label' => '48GB DDR5 high-speed kit', 'description' => 'Більше простору для багатозадачності й ігор.', 'price' => 2200, 'selected' => false],
                    ['key' => '64gb', 'label' => '64GB DDR5 creator kit', 'description' => 'Варіант для важких ігор, стрімів і робочих задач.', 'price' => 4400, 'selected' => false],
                ],
            ],
            [
                'id' => 'cooling',
                'title' => 'Встановлення охолодження CPU',
                'description' => null,
                'options' => [
                    ['key' => 'air', 'label' => 'Tower air cooler', 'description' => 'Надійне базове охолодження для щоденного геймінгу.', 'price' => 0, 'selected' => false],
                    ['key' => 'aio240', 'label' => '240mm AIO RGB', 'description' => 'Тихіша робота та кращий thermal headroom.', 'price' => 2600, 'selected' => true],
                    ['key' => 'aio360', 'label' => '360mm AIO premium', 'description' => 'Максимальний запас під boost і custom curve.', 'price' => 4800, 'selected' => false],
                ],
            ],
            [
                'id' => 'board',
                'title' => 'Заміна плати',
                'description' => null,
                'options' => [
                    ['key' => 'base', 'label' => $boardBase, 'description' => 'Поточний клас плати під обрану платформу.', 'price' => 0, 'selected' => true],
                    ['key' => 'white', 'label' => $boardBase . ' White edition', 'description' => 'Візуальний апгрейд під світлу збірку.', 'price' => 1900, 'selected' => false],
                    ['key' => 'creator', 'label' => 'Creator / OC motherboard', 'description' => 'Покращене VRM, більше портів і запасу для апгрейду.', 'price' => 3800, 'selected' => false],
                ],
            ],
            [
                'id' => 'adapters',
                'title' => 'Додавання адаптерів',
                'description' => null,
                'options' => [
                    ['key' => 'none', 'label' => 'Без додаткових адаптерів', 'description' => 'Тільки базові інтерфейси конфігурації.', 'price' => 0, 'selected' => false],
                    ['key' => 'wifi', 'label' => 'Wi-Fi 6E + Bluetooth 5.3', 'description' => 'Бездротові підключення для периферії й мережі.', 'price' => 1600, 'selected' => true],
                    ['key' => 'capture', 'label' => 'Capture / expansion kit', 'description' => 'Для стріму, другого монітора та розширення портів.', 'price' => 2800, 'selected' => false],
                ],
            ],
            [
                'id' => 'memory',
                'title' => 'Більше пам\'яті',
                'description' => null,
                'options' => [
                    ['key' => 'base', 'label' => $storage, 'description' => 'Базовий накопичувач збірки.', 'price' => 0, 'selected' => true],
                    ['key' => '1tb', 'label' => '+1TB Gen4 NVMe', 'description' => 'Окремий швидкий диск під ігри та бібліотеку.', 'price' => 3000, 'selected' => false],
                    ['key' => '2tb', 'label' => '+2TB Gen4 NVMe', 'description' => 'Великий запас під AAA-проєкти та записи стрімів.', 'price' => 5200, 'selected' => false],
                ],
            ],
            [
                'id' => 'psu',
                'title' => 'Покращення БЖ',
                'description' => null,
                'options' => [
                    ['key' => 'base', 'label' => $powerUpgrade, 'description' => 'Стандарт під поточну конфігурацію.', 'price' => 0, 'selected' => true],
                    ['key' => '1000w', 'label' => '1000W Gold full-modular', 'description' => 'Запас під потужні апгрейди GPU.', 'price' => 2400, 'selected' => false],
                    ['key' => '1000w-platinum', 'label' => '1000W Platinum silent', 'description' => 'Преміум БЖ з покращеною акустикою.', 'price' => 4200, 'selected' => false],
                ],
            ],
            [
                'id' => 'case',
                'title' => 'Корпус',
                'description' => null,
                'options' => [
                    ['key' => 'base', 'label' => $caseLabel, 'description' => 'Базовий стиль під tone цієї збірки.', 'price' => 0, 'selected' => true],
                    ['key' => 'panoramic', 'label' => 'Panoramic white edition', 'description' => 'Більше скла, світлий екстер\'єр і акцентний look.', 'price' => 2900, 'selected' => false],
                    ['key' => 'airflow', 'label' => 'Airflow performance chassis', 'description' => 'Покращений забір повітря під довгі сесії.', 'price' => 3400, 'selected' => false],
                ],
            ],
        ];

        return array_map(function (array $group): array {
            $type = static::typeForGroup((string) ($group['id'] ?? 'other'));

            $group['options'] = array_map(function (array $option) use ($type): array {
                $imageUrl = $option['image_url'] ?? ComponentImages::placeholderUrl(
                    $type,
                    (string) ($option['label'] ?? ''),
                );

                return [
                    ...$option,
                    'image_url' => $imageUrl,
                    'image_urls' => array_values(array_filter(array_map(
                        static fn ($value): string => trim((string) $value),
                        is_array($option['image_urls'] ?? null) ? $option['image_urls'] : [$imageUrl],
                    ))),
                ];
            }, (array) ($group['options'] ?? []));

            return $group;
        }, $groups);
    }

    public static function resolveFallbackSelection(array $build, ?array $selection = null): array
    {
        $groups = static::fallbackForBuild($build);
        $selection = is_array($selection) ? $selection : [];
        $normalizedSelection = [];
        $summary = [];
        $additionalPrice = 0;

        foreach ($groups as $group) {
            $groupId = (string) ($group['id'] ?? '');
            $options = array_values((array) ($group['options'] ?? []));

            if ($groupId === '' || $options === []) {
                continue;
            }

            $defaultOption = collect($options)->firstWhere('selected', true) ?? $options[0];
            $selectedKey = (string) ($selection[$groupId] ?? ($defaultOption['key'] ?? ''));
            $selectedOption = collect($options)->firstWhere('key', $selectedKey) ?? $defaultOption ?? $options[0];

            if (! is_array($selectedOption)) {
                continue;
            }

            $selectedKey = (string) ($selectedOption['key'] ?? '');
            $defaultKey = (string) ($defaultOption['key'] ?? '');
            $price = (int) ($selectedOption['price'] ?? 0);

            $normalizedSelection[$groupId] = $selectedKey;
            $additionalPrice += $price;

            if ($selectedKey !== $defaultKey || $price > 0) {
                $summary[] = trim((string) ($group['title'] ?? 'Опція')) . ': ' . trim((string) ($selectedOption['label'] ?? 'Вибрано'));
            }
        }

        return [
            'enabled' => $groups !== [],
            'selection' => $normalizedSelection,
            'summary' => $summary,
            'additional_price' => $additionalPrice,
            'total_price' => (int) ($build['price_raw'] ?? 0) + $additionalPrice,
            'compatibility' => [
                'is_valid' => true,
                'messages' => [],
            ],
        ];
    }

    protected static function typeForGroup(string $groupId): string
    {
        return match ($groupId) {
            'gpu' => 'gpu',
            'cpu' => 'cpu',
            'ram' => 'ram',
            'cooling' => 'cooler',
            'board' => 'motherboard',
            'memory' => 'storage',
            'psu' => 'psu',
            'case' => 'case',
            'adapters' => 'adapters',
            'modding' => 'modding',
            default => 'other',
        };
    }
}
