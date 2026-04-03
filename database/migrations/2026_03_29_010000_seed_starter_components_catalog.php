<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @var array<int, string>
     */
    protected array $seededSlugs = [
        'asus-tuf-z790-plus-wifi-d5',
        'msi-mag-b760-tomahawk-wifi-d5',
        'gigabyte-z790-aorus-elite-ax',
        'asrock-z790-steel-legend-wifi',
        'asus-prime-b760m-a-wifi-d5',
        'msi-mag-b650-tomahawk-wifi',
        'asus-tuf-b650-plus-wifi',
        'gigabyte-b650-aorus-elite-ax',
        'asrock-b650e-steel-legend-wifi',
        'msi-b550m-mortar-max-wifi',
        'kingston-fury-beast-32gb-6000-xmp',
        'corsair-vengeance-32gb-6400-xmp',
        'gskill-ripjaws-s5-32gb-6000-xmp',
        'patriot-viper-venom-32gb-6400-xmp',
        'teamgroup-delta-rgb-32gb-7200-xmp',
        'gskill-flare-x5-32gb-6000-expo',
        'kingston-fury-beast-32gb-6000-expo',
        'corsair-vengeance-rgb-32gb-6000-expo',
        'teamgroup-delta-rgb-32gb-6000-expo',
        'lexar-ares-rgb-32gb-6400-expo',
        'asus-dual-rtx-4060-8g',
        'msi-rtx-4060-ti-16g-ventus-2x',
        'gigabyte-rtx-4070-super-windforce-oc',
        'msi-rtx-5070-gaming-trio-oc',
        'gigabyte-rtx-5080-gaming-oc',
        'sapphire-pulse-rx-7600-xt',
        'asus-dual-rx-7700-xt-oc',
        'sapphire-pure-rx-7800-xt',
        'xfx-merc-310-rx-7900-gre',
        'sapphire-nitro-plus-rx-7900-xtx',
        'bequiet-pure-power-12m-750',
        'corsair-rm850e-atx30',
        'msi-mag-a850gl-pcie5',
        'seasonic-focus-gx1000-atx30',
        'bequiet-straight-power-12-1000',
    ];

    public function up(): void
    {
        if (! Schema::hasTable('components')) {
            return;
        }

        $now = now();

        DB::table('components')->upsert(
            array_map(
                fn (array $row): array => $this->normalizeRow($row, $now->toDateTimeString()),
                $this->rows(),
            ),
            ['slug'],
            [
                'type',
                'name',
                'vendor',
                'sku',
                'summary',
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
            ],
        );
    }

    public function down(): void
    {
        if (! Schema::hasTable('components')) {
            return;
        }

        DB::table('components')->whereIn('slug', $this->seededSlugs)->delete();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function rows(): array
    {
        return [
            $this->motherboard('motherboard-intel-01', 'ASUS TUF Gaming Z790-Plus WiFi', 'asus-tuf-z790-plus-wifi-d5', 'ASUS', 'LGA1700', 'DDR5', 'ATX', 'Intel platform / PCIe 5.0 / Wi-Fi', 'intel', 110),
            $this->motherboard('motherboard-intel-02', 'MSI MAG B760 Tomahawk WiFi', 'msi-mag-b760-tomahawk-wifi-d5', 'MSI', 'LGA1700', 'DDR5', 'ATX', 'Intel platform / balanced gaming board', 'intel', 120),
            $this->motherboard('motherboard-intel-03', 'Gigabyte Z790 Aorus Elite AX', 'gigabyte-z790-aorus-elite-ax', 'Gigabyte', 'LGA1700', 'DDR5', 'ATX', 'Intel platform / stronger VRM / Wi-Fi', 'intel', 130),
            $this->motherboard('motherboard-intel-04', 'ASRock Z790 Steel Legend WiFi', 'asrock-z790-steel-legend-wifi', 'ASRock', 'LGA1700', 'DDR5', 'ATX', 'Intel platform / premium feature set', 'intel', 140),
            $this->motherboard('motherboard-intel-05', 'ASUS Prime B760M-A WiFi D5', 'asus-prime-b760m-a-wifi-d5', 'ASUS', 'LGA1700', 'DDR5', 'mATX', 'Intel platform / compact mATX board', 'intel', 150),

            $this->motherboard('motherboard-amd-01', 'MSI MAG B650 Tomahawk WiFi', 'msi-mag-b650-tomahawk-wifi', 'MSI', 'AM5', 'DDR5', 'ATX', 'AMD AM5 / Wi-Fi / strong VRM', 'amd', 210),
            $this->motherboard('motherboard-amd-02', 'ASUS TUF Gaming B650-Plus WiFi', 'asus-tuf-b650-plus-wifi', 'ASUS', 'AM5', 'DDR5', 'ATX', 'AMD AM5 / balanced gaming board', 'amd', 220),
            $this->motherboard('motherboard-amd-03', 'Gigabyte B650 Aorus Elite AX', 'gigabyte-b650-aorus-elite-ax', 'Gigabyte', 'AM5', 'DDR5', 'ATX', 'AMD AM5 / Aorus series / Wi-Fi', 'amd', 230),
            $this->motherboard('motherboard-amd-04', 'ASRock B650E Steel Legend WiFi', 'asrock-b650e-steel-legend-wifi', 'ASRock', 'AM5', 'DDR5', 'ATX', 'AMD AM5 / B650E / premium upgrade', 'amd', 240),
            $this->motherboard('motherboard-amd-05', 'MSI B550M Mortar MAX WiFi', 'msi-b550m-mortar-max-wifi', 'MSI', 'AM4', 'DDR4', 'mATX', 'AMD AM4 / compact board for DDR4 builds', 'amd', 250),

            $this->ram('ram-intel-01', 'Kingston Fury Beast 32GB DDR5-6000 XMP', 'kingston-fury-beast-32gb-6000-xmp', 'Kingston', 'DDR5', 2, 32, 6000, 'XMP-ready DDR5 kit for Intel builds', 'intel', 310),
            $this->ram('ram-intel-02', 'Corsair Vengeance 32GB DDR5-6400 XMP', 'corsair-vengeance-32gb-6400-xmp', 'Corsair', 'DDR5', 2, 32, 6400, 'Faster XMP kit for Intel gaming builds', 'intel', 320),
            $this->ram('ram-intel-03', 'G.Skill Ripjaws S5 32GB DDR5-6000 XMP', 'gskill-ripjaws-s5-32gb-6000-xmp', 'G.Skill', 'DDR5', 2, 32, 6000, 'Low-profile DDR5 XMP kit', 'intel', 330),
            $this->ram('ram-intel-04', 'Patriot Viper Venom 32GB DDR5-6400 XMP', 'patriot-viper-venom-32gb-6400-xmp', 'Patriot', 'DDR5', 2, 32, 6400, 'Performance XMP memory kit', 'intel', 340),
            $this->ram('ram-intel-05', 'TeamGroup Delta RGB 32GB DDR5-7200 XMP', 'teamgroup-delta-rgb-32gb-7200-xmp', 'TeamGroup', 'DDR5', 2, 32, 7200, 'High-speed RGB XMP memory kit', 'intel', 350),

            $this->ram('ram-amd-01', 'G.Skill Flare X5 32GB DDR5-6000 EXPO', 'gskill-flare-x5-32gb-6000-expo', 'G.Skill', 'DDR5', 2, 32, 6000, 'AMD EXPO-optimized kit for Ryzen builds', 'amd', 410),
            $this->ram('ram-amd-02', 'Kingston Fury Beast 32GB DDR5-6000 EXPO', 'kingston-fury-beast-32gb-6000-expo', 'Kingston', 'DDR5', 2, 32, 6000, 'Stable EXPO kit for AM5 gaming builds', 'amd', 420),
            $this->ram('ram-amd-03', 'Corsair Vengeance RGB 32GB DDR5-6000 EXPO', 'corsair-vengeance-rgb-32gb-6000-expo', 'Corsair', 'DDR5', 2, 32, 6000, 'RGB EXPO kit for showcase builds', 'amd', 430),
            $this->ram('ram-amd-04', 'TeamGroup Delta RGB 32GB DDR5-6000 EXPO', 'teamgroup-delta-rgb-32gb-6000-expo', 'TeamGroup', 'DDR5', 2, 32, 6000, 'EXPO memory with RGB lighting', 'amd', 440),
            $this->ram('ram-amd-05', 'Lexar Ares RGB 32GB DDR5-6400 EXPO', 'lexar-ares-rgb-32gb-6400-expo', 'Lexar', 'DDR5', 2, 32, 6400, 'Higher-clock EXPO kit for AM5', 'amd', 450),

            $this->gpu('gpu-nvidia-01', 'ASUS Dual GeForce RTX 4060 8GB', 'asus-dual-rtx-4060-8g', 'ASUS', 227, 115, 1, 'NVIDIA mid-range GPU for Full HD builds', 'nvidia', 510),
            $this->gpu('gpu-nvidia-02', 'MSI GeForce RTX 4060 Ti Ventus 2X 16G', 'msi-rtx-4060-ti-16g-ventus-2x', 'MSI', 199, 165, 1, 'NVIDIA upgrade for stronger Full HD / entry 2K', 'nvidia', 520),
            $this->gpu('gpu-nvidia-03', 'Gigabyte GeForce RTX 4070 Super Windforce OC', 'gigabyte-rtx-4070-super-windforce-oc', 'Gigabyte', 261, 220, 1, 'NVIDIA 2K gaming option', 'nvidia', 530),
            $this->gpu('gpu-nvidia-04', 'MSI GeForce RTX 5070 Gaming Trio OC', 'msi-rtx-5070-gaming-trio-oc', 'MSI', 338, 250, 1, 'NVIDIA next-tier 2K GPU', 'nvidia', 540),
            $this->gpu('gpu-nvidia-05', 'Gigabyte GeForce RTX 5080 Gaming OC', 'gigabyte-rtx-5080-gaming-oc', 'Gigabyte', 340, 360, 1, 'NVIDIA flagship-class upgrade for heavy builds', 'nvidia', 550),

            $this->gpu('gpu-amd-01', 'Sapphire Pulse Radeon RX 7600 XT 16GB', 'sapphire-pulse-rx-7600-xt', 'Sapphire', 267, 190, 2, 'Radeon option for strong Full HD builds', 'amd-radeon', 610),
            $this->gpu('gpu-amd-02', 'ASUS Dual Radeon RX 7700 XT OC', 'asus-dual-rx-7700-xt-oc', 'ASUS', 267, 245, 2, 'Radeon 2K-ready upgrade', 'amd-radeon', 620),
            $this->gpu('gpu-amd-03', 'Sapphire Pure Radeon RX 7800 XT', 'sapphire-pure-rx-7800-xt', 'Sapphire', 320, 263, 2, 'Radeon high-performance 2K card', 'amd-radeon', 630),
            $this->gpu('gpu-amd-04', 'XFX Speedster MERC 310 Radeon RX 7900 GRE', 'xfx-merc-310-rx-7900-gre', 'XFX', 335, 260, 2, 'Radeon upper-mid 2K / 4K-ready option', 'amd-radeon', 640),
            $this->gpu('gpu-amd-05', 'Sapphire Nitro+ Radeon RX 7900 XTX', 'sapphire-nitro-plus-rx-7900-xtx', 'Sapphire', 320, 355, 3, 'Radeon flagship upgrade for premium builds', 'amd-radeon', 650),

            $this->psu('psu-01', 'be quiet! Pure Power 12 M 750W', 'bequiet-pure-power-12m-750', 'be quiet!', 750, 3, 'Quiet ATX 3.0 PSU for balanced builds', 710),
            $this->psu('psu-02', 'Corsair RM850e ATX 3.0', 'corsair-rm850e-atx30', 'Corsair', 850, 4, 'Popular 850W PSU for stronger GPUs', 720),
            $this->psu('psu-03', 'MSI MAG A850GL PCIE5', 'msi-mag-a850gl-pcie5', 'MSI', 850, 4, '850W Gold PSU with PCIe 5 support', 730),
            $this->psu('psu-04', 'Seasonic Focus GX-1000 ATX 3.0', 'seasonic-focus-gx1000-atx30', 'Seasonic', 1000, 5, 'Reliable 1000W upgrade for high-end builds', 740),
            $this->psu('psu-05', 'be quiet! Straight Power 12 1000W', 'bequiet-straight-power-12-1000', 'be quiet!', 1000, 5, 'Premium 1000W quiet PSU for top builds', 750),
        ];
    }

    protected function motherboard(
        string $sku,
        string $name,
        string $slug,
        string $vendor,
        string $socket,
        string $ramType,
        string $formFactor,
        string $summary,
        string $platform,
        int $sortOrder,
    ): array {
        return [
            'type' => 'motherboard',
            'name' => $name,
            'slug' => $slug,
            'vendor' => $vendor,
            'sku' => $sku,
            'summary' => $summary,
            'socket' => $socket,
            'ram_type' => $ramType,
            'form_factor' => $formFactor,
            'meta' => ['starter_catalog' => true, 'platform' => $platform],
            'sort_order' => $sortOrder,
            'is_active' => true,
        ];
    }

    protected function ram(
        string $sku,
        string $name,
        string $slug,
        string $vendor,
        string $ramType,
        int $modules,
        int $capacity,
        int $speed,
        string $summary,
        string $platform,
        int $sortOrder,
    ): array {
        return [
            'type' => 'ram',
            'name' => $name,
            'slug' => $slug,
            'vendor' => $vendor,
            'sku' => $sku,
            'summary' => $summary,
            'ram_type' => $ramType,
            'memory_modules' => $modules,
            'memory_capacity_gb' => $capacity,
            'memory_speed_mhz' => $speed,
            'meta' => ['starter_catalog' => true, 'platform' => $platform],
            'sort_order' => $sortOrder,
            'is_active' => true,
        ];
    }

    protected function gpu(
        string $sku,
        string $name,
        string $slug,
        string $vendor,
        int $length,
        int $power,
        int $connectors,
        string $summary,
        string $family,
        int $sortOrder,
    ): array {
        return [
            'type' => 'gpu',
            'name' => $name,
            'slug' => $slug,
            'vendor' => $vendor,
            'sku' => $sku,
            'summary' => $summary,
            'gpu_length_mm' => $length,
            'gpu_power_w' => $power,
            'gpu_power_connectors' => $connectors,
            'meta' => ['starter_catalog' => true, 'family' => $family],
            'sort_order' => $sortOrder,
            'is_active' => true,
        ];
    }

    protected function psu(
        string $sku,
        string $name,
        string $slug,
        string $vendor,
        int $wattage,
        int $connectors,
        string $summary,
        int $sortOrder,
    ): array {
        return [
            'type' => 'psu',
            'name' => $name,
            'slug' => $slug,
            'vendor' => $vendor,
            'sku' => $sku,
            'summary' => $summary,
            'psu_wattage' => $wattage,
            'pcie_power_connectors' => $connectors,
            'meta' => ['starter_catalog' => true, 'platform' => 'universal'],
            'sort_order' => $sortOrder,
            'is_active' => true,
        ];
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    protected function normalizeRow(array $row, string $timestamp): array
    {
        return [
            'type' => $row['type'],
            'name' => $row['name'],
            'slug' => $row['slug'],
            'vendor' => $row['vendor'] ?? null,
            'sku' => $row['sku'] ?? null,
            'summary' => $row['summary'] ?? null,
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
            'sort_order' => $row['sort_order'] ?? 0,
            'is_active' => $row['is_active'] ?? true,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ];
    }

    protected function jsonValue(mixed $value): ?string
    {
        if (! is_array($value) || $value === []) {
            return null;
        }

        return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
};
