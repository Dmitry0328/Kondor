<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        DB::table('users')->updateOrInsert(
            ['email' => (string) env('ADMIN_DEFAULT_EMAIL', 'admin@kondor.local')],
            [
                'name' => (string) env('ADMIN_DEFAULT_NAME', 'Admin'),
                'email_verified_at' => null,
                'is_admin' => true,
                'password' => Hash::make((string) env('ADMIN_DEFAULT_PASSWORD', 'change-me-now')),
                'remember_token' => null,
                'updated_at' => $now,
                'created_at' => $now,
            ],
        );

        DB::table('builds')->upsert($this->buildRows($now), ['slug'], [
            'tone',
            'name',
            'product_code',
            'gpu',
            'cpu',
            'ram',
            'storage',
            'price',
            'fps_score',
            'fps_profiles',
            'product_specs',
            'about',
            'base_components',
            'configurator_groups',
            'sort_order',
            'is_active',
            'updated_at',
        ]);

        DB::table('components')->upsert($this->componentRows($now), ['slug'], [
            'type',
            'name',
            'vendor',
            'sku',
            'price',
            'summary',
            'gallery_paths',
            'socket',
            'ram_type',
            'form_factor',
            'supported_mb_form_factors',
            'supported_sockets',
            'supported_radiator_sizes',
            'max_gpu_length_mm',
            'max_cooler_height_mm',
            'gpu_length_mm',
            'gpu_power_w',
            'gpu_power_connectors',
            'cpu_tdp_w',
            'psu_wattage',
            'pcie_power_connectors',
            'radiator_size_mm',
            'memory_modules',
            'memory_capacity_gb',
            'memory_speed_mhz',
            'storage_interface',
            'meta',
            'sort_order',
            'is_active',
            'updated_at',
        ]);
    }

    public function down(): void
    {
        DB::table('components')->whereIn('slug', [
            'korpus-qube-reef-argb-black-reef-gbnu3',
            'korpus-qube-mirage-black-mirage-gbnu3',
        ])->delete();

        DB::table('builds')->whereIn('slug', [
            'phantom',
            'nova',
            'vector',
            'crystal',
            'storm',
        ])->delete();

        DB::table('users')->where('email', (string) env('ADMIN_DEFAULT_EMAIL', 'admin@kondor.local'))->delete();
    }

    protected function buildRows($now): array
    {
        return [
            $this->buildRow($now, [
                'slug' => 'phantom',
                'tone' => 'violet',
                'name' => 'Ігровий ПК "Phantom"',
                'product_code' => '570001',
                'gpu' => 'Nvidia RTX 4070 Super',
                'cpu' => 'AMD Ryzen 7 7700',
                'ram' => '32GB DDR5 6000 MHz',
                'storage' => 'SSD M.2 NVMe 1TB',
                'price' => 69990,
                'fps_score' => 146,
                'fps_profiles' => [['fps' => 146, 'game' => 'cyberpunk-2077', 'preset' => 'high', 'display' => '1440p']],
                'sort_order' => 1,
            ]),
            $this->buildRow($now, [
                'slug' => 'nova',
                'tone' => 'magenta',
                'name' => 'Ігровий ПК "Nova"',
                'product_code' => '570002',
                'gpu' => 'AMD Radeon RX 7800 XT',
                'cpu' => 'AMD Ryzen 7 7800X3D',
                'ram' => '32GB DDR5 6000 MHz',
                'storage' => 'SSD M.2 NVMe 1TB',
                'price' => 82990,
                'fps_score' => 156,
                'fps_profiles' => [['fps' => 156, 'game' => 'cyberpunk-2077', 'preset' => 'high', 'display' => '1440p']],
                'sort_order' => 2,
            ]),
            $this->buildRow($now, [
                'slug' => 'vector',
                'tone' => 'amber',
                'name' => 'Ігровий ПК "Vector"',
                'product_code' => '570003',
                'gpu' => 'Nvidia RTX 3060 12GB GDDR6',
                'cpu' => 'Ryzen 5 3600X / 3600 (в залежності від наявності) 6C/12T 4.4GHz',
                'ram' => "16GB DDR4 2666-3200MHz (в залежності від наявності)",
                'storage' => 'SSD 2.5 120GB + HDD 500GB',
                'price' => 61990,
                'fps_score' => 116,
                'fps_profiles' => [['fps' => 116, 'game' => 'cyberpunk-2077', 'preset' => 'high', 'display' => '1440p']],
                'product_specs' => [
                    ['icon' => 'gpu', 'label' => 'Відеокарта', 'value' => 'Nvidia RTX 3060 12GB GDDR6'],
                    ['icon' => 'cpu', 'label' => 'Процесор', 'value' => 'Ryzen 5 3600X / 3600 (в залежності від наявності) 6C/12T 4.4GHz'],
                    ['icon' => 'ram', 'label' => "Оперативна пам'ять", 'value' => "16GB DDR4 2666-3200MHz (в залежності від наявності)"],
                    ['icon' => 'motherboard', 'label' => 'Материнська плата', 'value' => 'Asus B450M-K або якісні аналоги'],
                    ['icon' => 'storage', 'label' => 'Накопичувач', 'value' => 'SSD 2.5 120GB + HDD 500GB'],
                    ['icon' => 'case', 'label' => 'Корпус', 'value' => 'Як на фото або інші на вибір'],
                    ['icon' => 'psu', 'label' => 'Блок живлення', 'value' => '600+ Ватт (лише якісні, з APFC)'],
                ],
                'sort_order' => 3,
            ]),
            $this->buildRow($now, [
                'slug' => 'crystal',
                'tone' => 'peach',
                'name' => 'Ігровий ПК "Crystal"',
                'product_code' => '570004',
                'gpu' => 'Nvidia RTX 5070',
                'cpu' => 'AMD Ryzen 7 9700X',
                'ram' => '32GB DDR5 6400 MHz',
                'storage' => 'SSD M.2 NVMe 2TB',
                'price' => 94990,
                'fps_score' => 168,
                'fps_profiles' => [['fps' => 168, 'game' => 'cyberpunk-2077', 'preset' => 'high', 'display' => '1440p']],
                'sort_order' => 4,
            ]),
            $this->buildRow($now, [
                'slug' => 'storm',
                'tone' => 'emerald',
                'name' => 'Ігровий ПК "Storm"',
                'product_code' => '570005',
                'gpu' => 'AMD Radeon RX 7900 GRE',
                'cpu' => 'AMD Ryzen 5 9600X',
                'ram' => '32GB DDR5 6000 MHz',
                'storage' => 'SSD M.2 NVMe 1TB',
                'price' => 74990,
                'fps_score' => 132,
                'fps_profiles' => [['fps' => 132, 'game' => 'cyberpunk-2077', 'preset' => 'high', 'display' => '1440p']],
                'sort_order' => 5,
            ]),
        ];
    }

    protected function componentRows($now): array
    {
        return [
            $this->componentRow($now, [
                'type' => 'case',
                'name' => 'Корпус QUBE REEF ARGB Black (REEF_GBNU3)',
                'slug' => 'korpus-qube-reef-argb-black-reef-gbnu3',
                'vendor' => 'QUBE',
                'sku' => '00-00066455',
                'price' => 0,
                'summary' => 'Корпус QUBE REEF ARGB Black (REEF_GBNU3) / MiddleTower / M-ATX | mini-ITX / Side: 2x120mm / Top: 2x120mm / 1 x USB 3.0 Type A + 2 x USB 2.0 Type A + Audio + Mic / 2x3.5", 1x2.5" / 375x270x340 / Чорний / 12міс. / REEF_GBNU3',
                'gallery_paths' => [
                    'components/01KNFK1DR2VDH3RJN2EMQX0X2A.webp',
                    'components/01KNFK1DR5EH9RV6FY4BX6QGAK.webp',
                    'components/01KNFK1DR7MGZA718KKMJ584RX.webp',
                    'components/01KNFK1DR8NNPTDQ6PYRNQ0DAH.webp',
                    'components/01KNFK1DRAK56T4YH8N4MN1HHM.webp',
                    'components/01KNFK1DRD78GRB89W0323KDFF.webp',
                    'components/01KNFK1DRF6MF7AHXMXFF930CP.webp',
                    'components/01KNFK1DRJZKNXBY9T5WEMJ7CV.webp',
                    'components/01KNFK1DRMFZN99G99PQVSY0XB.webp',
                    'components/01KNFK1DRPFPRT27CH5E4B22XW.webp',
                ],
                'supported_mb_form_factors' => ['mini-ITX', 'mATX'],
                'supported_sockets' => [],
                'supported_radiator_sizes' => [240],
                'max_gpu_length_mm' => 320,
                'max_cooler_height_mm' => 160,
                'sort_order' => 10,
                'is_active' => true,
            ]),
            $this->componentRow($now, [
                'type' => 'case',
                'name' => 'Корпус QUBE Mirage Black (MIRAGE_GBNU3)',
                'slug' => 'korpus-qube-mirage-black-mirage-gbnu3',
                'vendor' => 'QUBE',
                'sku' => 'АЛ-00010436',
                'price' => 0,
                'summary' => 'Корпус QUBE Mirage Black (MIRAGE_GBNU3) / MiddleTower / ATX | microATX | mini-ITX / Bottom: 3x120mm / Side: 3x120mm / Rear: 1x120mm / 2 x USB 2.0 + 1 x USB 3.0 + Audio + Mic / 2x3.5" / 1x2.5" / 450х285х435 (ДxШxВ) / Чорний / 12міс. / MIRAGE_GBNU3',
                'gallery_paths' => [
                    'components/01KNFPW008NZFBS62CNV78BYM3.png',
                ],
                'supported_mb_form_factors' => ['ATX', 'mATX', 'mini-ITX'],
                'supported_sockets' => [],
                'supported_radiator_sizes' => [],
                'max_gpu_length_mm' => 410,
                'max_cooler_height_mm' => 165,
                'sort_order' => 20,
                'is_active' => true,
            ]),
        ];
    }

    protected function buildRow($now, array $row): array
    {
        return [
            'slug' => $row['slug'],
            'tone' => $row['tone'] ?? 'violet',
            'name' => $row['name'],
            'product_code' => $row['product_code'] ?? null,
            'gpu' => $row['gpu'] ?? null,
            'cpu' => $row['cpu'] ?? null,
            'ram' => $row['ram'] ?? null,
            'storage' => $row['storage'] ?? null,
            'price' => (int) ($row['price'] ?? 0),
            'fps_score' => (int) ($row['fps_score'] ?? 0),
            'fps_profiles' => $this->jsonValue($row['fps_profiles'] ?? null),
            'product_specs' => $this->jsonValue($row['product_specs'] ?? null),
            'about' => $this->jsonValue($row['about'] ?? null),
            'base_components' => $this->jsonValue($row['base_components'] ?? null),
            'configurator_groups' => $this->jsonValue($row['configurator_groups'] ?? null),
            'sort_order' => (int) ($row['sort_order'] ?? 0),
            'is_active' => (bool) ($row['is_active'] ?? true),
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }

    protected function componentRow($now, array $row): array
    {
        return [
            'type' => $row['type'],
            'name' => $row['name'],
            'slug' => $row['slug'],
            'vendor' => $row['vendor'] ?? null,
            'sku' => $row['sku'] ?? null,
            'price' => (int) ($row['price'] ?? 0),
            'summary' => $row['summary'] ?? null,
            'gallery_paths' => $this->jsonValue($row['gallery_paths'] ?? null),
            'socket' => $row['socket'] ?? null,
            'ram_type' => $row['ram_type'] ?? null,
            'form_factor' => $row['form_factor'] ?? null,
            'supported_mb_form_factors' => $this->jsonValue($row['supported_mb_form_factors'] ?? null),
            'supported_sockets' => $this->jsonValue($row['supported_sockets'] ?? null),
            'supported_radiator_sizes' => $this->jsonValue($row['supported_radiator_sizes'] ?? null),
            'max_gpu_length_mm' => $row['max_gpu_length_mm'] ?? null,
            'max_cooler_height_mm' => $row['max_cooler_height_mm'] ?? null,
            'gpu_length_mm' => $row['gpu_length_mm'] ?? null,
            'gpu_power_w' => $row['gpu_power_w'] ?? null,
            'gpu_power_connectors' => $row['gpu_power_connectors'] ?? null,
            'cpu_tdp_w' => $row['cpu_tdp_w'] ?? null,
            'psu_wattage' => $row['psu_wattage'] ?? null,
            'pcie_power_connectors' => $row['pcie_power_connectors'] ?? null,
            'radiator_size_mm' => $row['radiator_size_mm'] ?? null,
            'memory_modules' => $row['memory_modules'] ?? null,
            'memory_capacity_gb' => $row['memory_capacity_gb'] ?? null,
            'memory_speed_mhz' => $row['memory_speed_mhz'] ?? null,
            'storage_interface' => $row['storage_interface'] ?? null,
            'meta' => $this->jsonValue($row['meta'] ?? null),
            'sort_order' => (int) ($row['sort_order'] ?? 0),
            'is_active' => (bool) ($row['is_active'] ?? true),
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }

    protected function jsonValue(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value === []) {
            return json_encode([], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
};
