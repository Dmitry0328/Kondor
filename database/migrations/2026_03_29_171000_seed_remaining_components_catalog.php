<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('components')) {
            return;
        }

        $now = now()->toDateTimeString();

        DB::table('components')->upsert(
            array_map(
                fn (array $row): array => $this->normalizeRow($row, $now),
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

        DB::table('components')
            ->whereIn('slug', collect($this->rows())->pluck('slug')->all())
            ->delete();
    }

    protected function rows(): array
    {
        return [
            $this->cpu('cpu-amd-01', 'AMD Ryzen 5 3600X', 'amd-ryzen-5-3600x', 'AMD', 'AM4', 95, '6/12, good value for AM4 gaming builds', ['family' => 'AMD', 'color' => 'Silver'], 810),
            $this->cpu('cpu-amd-02', 'AMD Ryzen 7 7700', 'amd-ryzen-7-7700', 'AMD', 'AM5', 65, 'Balanced AM5 CPU for gaming and daily use', ['family' => 'AMD', 'color' => 'Silver'], 820),
            $this->cpu('cpu-amd-03', 'AMD Ryzen 7 7800X3D', 'amd-ryzen-7-7800x3d', 'AMD', 'AM5', 120, 'Top gaming CPU for high-refresh builds', ['family' => 'AMD', 'color' => 'Silver'], 830),
            $this->cpu('cpu-amd-04', 'AMD Ryzen 7 9700X', 'amd-ryzen-7-9700x', 'AMD', 'AM5', 65, 'Zen 5 CPU for gaming and creator workloads', ['family' => 'AMD', 'color' => 'Silver'], 840),
            $this->cpu('cpu-amd-05', 'AMD Ryzen 5 9600X', 'amd-ryzen-5-9600x', 'AMD', 'AM5', 65, 'Modern AM5 option for efficient gaming PCs', ['family' => 'AMD', 'color' => 'Silver'], 850),

            $this->cpu('cpu-intel-01', 'Intel Core i5-12400F', 'intel-core-i5-12400f', 'Intel', 'LGA1700', 65, 'Entry Intel gaming option with strong single-core', ['family' => 'Intel', 'color' => 'Blue'], 860),
            $this->cpu('cpu-intel-02', 'Intel Core i5-14600KF', 'intel-core-i5-14600kf', 'Intel', 'LGA1700', 125, 'Popular Intel gaming CPU with strong boost clocks', ['family' => 'Intel', 'color' => 'Blue'], 870),
            $this->cpu('cpu-intel-03', 'Intel Core i7-14700KF', 'intel-core-i7-14700kf', 'Intel', 'LGA1700', 125, 'Higher-core Intel CPU for gaming and streaming', ['family' => 'Intel', 'color' => 'Blue'], 880),
            $this->cpu('cpu-intel-04', 'Intel Core i9-14900KF', 'intel-core-i9-14900kf', 'Intel', 'LGA1700', 125, 'Flagship Intel option for heavy mixed workloads', ['family' => 'Intel', 'color' => 'Blue'], 890),
            $this->cpu('cpu-intel-05', 'Intel Core Ultra 7 265K', 'intel-core-ultra-7-265k', 'Intel', 'LGA1851', 125, 'Next-gen Intel option for premium builds', ['family' => 'Intel', 'color' => 'Blue'], 900),

            $this->storage('storage-01', 'WD Black SN850X 1TB', 'wd-black-sn850x-1tb', 'Western Digital', 'wd-black-sn850x-1tb', 'High-speed Gen4 NVMe for system and games', 'NVMe', 1000, ['color' => 'Black'], 910),
            $this->storage('storage-02', 'Samsung 990 Pro 2TB', 'samsung-990-pro-2tb', 'Samsung', 'samsung-990-pro-2tb', 'Premium Gen4 NVMe with high sustained speed', 'NVMe', 2000, ['color' => 'Black'], 920),
            $this->storage('storage-03', 'Kingston KC3000 1TB', 'kingston-kc3000-1tb', 'Kingston', 'kingston-kc3000-1tb', 'Fast and stable Gen4 SSD for gaming builds', 'NVMe', 1000, ['color' => 'Black'], 930),
            $this->storage('storage-04', 'Lexar NM790 2TB', 'lexar-nm790-2tb', 'Lexar', 'lexar-nm790-2tb', 'Balanced 2TB NVMe for large game libraries', 'NVMe', 2000, ['color' => 'Black'], 940),
            $this->storage('storage-05', 'Crucial MX500 1TB', 'crucial-mx500-1tb', 'Crucial', 'crucial-mx500-1tb', 'Classic SATA SSD for extra storage and archives', 'SATA', 1000, ['color' => 'Black'], 950),

            $this->pcCase('case-01', 'NZXT H6 Flow RGB White', 'nzxt-h6-flow-rgb-white', 'NZXT', 'ATX', 365, 163, ['ATX', 'mATX', 'ITX'], ['240', '280', '360'], 'Panoramic white case for showcase builds', ['color' => 'White'], 1010),
            $this->pcCase('case-02', 'Lian Li Lancool 216 Black', 'lian-li-lancool-216-black', 'Lian Li', 'ATX', 392, 180, ['ATX', 'mATX', 'ITX'], ['240', '280', '360'], 'Airflow-focused black case with strong cooling', ['color' => 'Black'], 1020),
            $this->pcCase('case-03', 'Corsair 4000D Airflow', 'corsair-4000d-airflow', 'Corsair', 'ATX', 360, 170, ['ATX', 'mATX', 'ITX'], ['240', '280', '360'], 'Well-known airflow mid-tower for balanced PCs', ['color' => 'Black'], 1030),
            $this->pcCase('case-04', 'Montech King 95 Pro White', 'montech-king-95-pro-white', 'Montech', 'ATX', 420, 175, ['ATX', 'mATX', 'ITX'], ['240', '280', '360'], 'Large dual-chamber panoramic case for premium builds', ['color' => 'White'], 1040),
            $this->pcCase('case-05', 'DeepCool CH560 Digital', 'deepcool-ch560-digital', 'DeepCool', 'ATX', 380, 175, ['ATX', 'mATX', 'ITX'], ['240', '280', '360'], 'Airflow case with display panel and roomy interior', ['color' => 'Black'], 1050),

            $this->cooler('cooler-01', 'DeepCool AK400', 'deepcool-ak400', 'DeepCool', ['AM4', 'AM5', 'LGA1700'], 155, 0, 'Reliable tower cooler for mainstream CPUs', ['color' => 'Black'], 1110),
            $this->cooler('cooler-02', 'DeepCool AK620 Digital', 'deepcool-ak620-digital', 'DeepCool', ['AM4', 'AM5', 'LGA1700'], 162, 0, 'Dual-tower air cooler with display', ['color' => 'Black'], 1120),
            $this->cooler('cooler-03', 'Arctic Liquid Freezer III 240', 'arctic-liquid-freezer-iii-240', 'ARCTIC', ['AM4', 'AM5', 'LGA1700'], 0, 240, 'Efficient 240mm AIO for quieter high-load use', ['color' => 'Black'], 1130),
            $this->cooler('cooler-04', 'MSI MAG CoreLiquid E360', 'msi-mag-coreliquid-e360', 'MSI', ['AM5', 'LGA1700'], 0, 360, '360mm AIO for flagship CPUs', ['color' => 'Black'], 1140),
            $this->cooler('cooler-05', 'be quiet! Dark Rock 5', 'bequiet-dark-rock-5', 'be quiet!', ['AM4', 'AM5', 'LGA1700'], 161, 0, 'Premium silent air cooler', ['color' => 'Black'], 1150),

            $this->generic('fans', 'ARCTIC P12 PWM PST 3-pack', 'arctic-p12-pwm-pst-3pack', 'ARCTIC', 'arctic-p12-pwm-pst-3pack', '120mm PWM fan kit for airflow upgrades', ['color' => 'Black', 'size' => '120mm'], 1210),
            $this->generic('fans', 'DeepCool FC120 ARGB 3-pack', 'deepcool-fc120-argb-3pack', 'DeepCool', 'deepcool-fc120-argb-3pack', 'ARGB fan kit for showcase builds', ['color' => 'Black', 'size' => '120mm'], 1220),
            $this->generic('fans', 'Lian Li UNI FAN SL Infinity 3-pack', 'lian-li-uni-fan-sl-infinity-3pack', 'Lian Li', 'lian-li-sl-infinity-3pack', 'Premium daisy-chain RGB fans', ['color' => 'Black', 'size' => '120mm'], 1230),
            $this->generic('fans', 'NZXT F120 RGB Duo 3-pack', 'nzxt-f120-rgb-duo-3pack', 'NZXT', 'nzxt-f120-rgb-duo-3pack', 'RGB fan kit for NZXT ecosystem', ['color' => 'White', 'size' => '120mm'], 1240),
            $this->generic('fans', 'be quiet! Silent Wings 4 140mm 2-pack', 'bequiet-silent-wings-4-140-2pack', 'be quiet!', 'bequiet-silent-wings-4-140-2pack', 'Quiet 140mm airflow upgrade', ['color' => 'Black', 'size' => '140mm'], 1250),

            $this->generic('lighting', 'Phanteks Neon ARGB Strip Kit', 'phanteks-neon-argb-strip-kit', 'Phanteks', 'phanteks-neon-strip-kit', 'Flexible ARGB strips for ambient lighting', ['color' => 'RGB'], 1310),
            $this->generic('lighting', 'Corsair iCUE Lighting Node Pro', 'corsair-icue-lighting-node-pro', 'Corsair', 'corsair-lighting-node-pro', 'Controller for RGB strips and profiles', ['color' => 'Black'], 1320),
            $this->generic('lighting', 'NZXT RGB & Fan Controller', 'nzxt-rgb-fan-controller', 'NZXT', 'nzxt-rgb-fan-controller', 'Controller for RGB and fan synchronization', ['color' => 'Black'], 1330),
            $this->generic('lighting', 'Lian Li Strimer Plus V2 24-pin', 'lian-li-strimer-plus-v2-24pin', 'Lian Li', 'lian-li-strimer-v2-24pin', 'ARGB illuminated motherboard power cable', ['color' => 'RGB'], 1340),
            $this->generic('lighting', 'DeepCool RGB 200 Pro', 'deepcool-rgb-200-pro', 'DeepCool', 'deepcool-rgb-200-pro', 'RGB strip kit for subtle internal lighting', ['color' => 'RGB'], 1350),

            $this->generic('network', 'Intel AX210 Wi-Fi 6E PCIe', 'intel-ax210-wifi-6e-pcie', 'Intel', 'intel-ax210-pcie', 'Wi-Fi 6E + Bluetooth upgrade card', ['color' => 'Black'], 1410),
            $this->generic('network', 'ASUS PCE-AX58BT', 'asus-pce-ax58bt', 'ASUS', 'asus-pce-ax58bt', 'Popular Wi-Fi 6 + Bluetooth PCIe card', ['color' => 'Black'], 1420),
            $this->generic('network', 'TP-Link Archer TX3000E', 'tplink-archer-tx3000e', 'TP-Link', 'tplink-tx3000e', 'Dual-antenna Wi-Fi adapter for desktops', ['color' => 'Black'], 1430),
            $this->generic('network', 'Intel I225-V 2.5G PCIe NIC', 'intel-i225v-25g-pcie-nic', 'Intel', 'intel-i225v-25g', '2.5G Ethernet add-in card for faster LAN', ['color' => 'Black'], 1440),
            $this->generic('network', 'UGREEN USB-C 2.5G Ethernet', 'ugreen-usbc-25g-ethernet', 'UGREEN', 'ugreen-usbc-25g', 'USB-C network adapter for laptops and builds', ['color' => 'Gray'], 1450),

            $this->generic('sound', 'ASUS Xonar SE', 'asus-xonar-se', 'ASUS', 'asus-xonar-se', 'Entry internal sound card upgrade', ['color' => 'Black'], 1510),
            $this->generic('sound', 'Creative Sound Blaster Audigy FX V2', 'creative-audigy-fx-v2', 'Creative', 'creative-audigy-fx-v2', 'Affordable PCIe audio upgrade', ['color' => 'Black'], 1520),
            $this->generic('sound', 'Creative Sound BlasterX AE-5 Plus', 'creative-sound-blasterx-ae5-plus', 'Creative', 'creative-ae5-plus', 'Higher-end gaming sound card', ['color' => 'Black'], 1530),
            $this->generic('sound', 'FiiO K11 DAC/AMP', 'fiio-k11-dac-amp', 'FiiO', 'fiio-k11', 'External DAC/AMP for headsets and speakers', ['color' => 'Black'], 1540),
            $this->generic('sound', 'HyperX USB Audio Control Box', 'hyperx-usb-audio-control-box', 'HyperX', 'hyperx-audio-box', 'Simple external audio adapter for headsets', ['color' => 'Black'], 1550),

            $this->generic('capture', 'Elgato HD60 X', 'elgato-hd60-x', 'Elgato', 'elgato-hd60-x', 'External capture for console and PC streaming', ['color' => 'Black'], 1610),
            $this->generic('capture', 'AVerMedia Live Gamer Mini', 'avermedia-live-gamer-mini', 'AVerMedia', 'avermedia-lgm', 'Compact capture card for streaming basics', ['color' => 'Black'], 1620),
            $this->generic('capture', 'Elgato Cam Link 4K', 'elgato-cam-link-4k', 'Elgato', 'elgato-cam-link-4k', 'Capture DSLR or action camera as webcam', ['color' => 'Black'], 1630),
            $this->generic('capture', 'Elgato 4K60 Pro MK.2', 'elgato-4k60-pro-mk2', 'Elgato', 'elgato-4k60-pro-mk2', 'Internal capture card for high-end streaming rigs', ['color' => 'Black'], 1640),
            $this->generic('capture', 'AVerMedia Live Gamer HD 2', 'avermedia-live-gamer-hd2', 'AVerMedia', 'avermedia-lghd2', 'Internal PCIe capture solution', ['color' => 'Black'], 1650),

            $this->generic('adapters', 'TP-Link UB500 Bluetooth 5.0 USB', 'tplink-ub500-bluetooth-5-usb', 'TP-Link', 'tplink-ub500', 'USB Bluetooth adapter for peripherals', ['color' => 'Black'], 1710),
            $this->generic('adapters', 'UGREEN USB-C Hub HDMI LAN', 'ugreen-usbc-hub-hdmi-lan', 'UGREEN', 'ugreen-usbc-hub-hdmi-lan', 'Multiport hub with HDMI and LAN', ['color' => 'Gray'], 1720),
            $this->generic('adapters', 'ORICO PCIe USB 3.2 Expansion Card', 'orico-pcie-usb32-expansion-card', 'ORICO', 'orico-pcie-usb32', 'Adds more USB ports to the build', ['color' => 'Black'], 1730),
            $this->generic('adapters', 'CableMod 12VHPWR 90° Adapter', 'cablemod-12vhpwr-90-adapter', 'CableMod', 'cablemod-12vhpwr-90', 'Cleaner GPU cable routing adapter', ['color' => 'Black'], 1740),
            $this->generic('adapters', 'SilverStone ARGB Hub', 'silverstone-argb-hub', 'SilverStone', 'silverstone-argb-hub', 'ARGB hub for multiple lighting devices', ['color' => 'Black'], 1750),

            $this->generic('modding', 'CableMod PRO ModMesh Kit Black', 'cablemod-pro-modmesh-kit-black', 'CableMod', 'cablemod-pro-black', 'Premium sleeved cable kit for black builds', ['color' => 'Black'], 1810),
            $this->generic('modding', 'CableMod PRO ModMesh Kit White', 'cablemod-pro-modmesh-kit-white', 'CableMod', 'cablemod-pro-white', 'Premium sleeved cable kit for white builds', ['color' => 'White'], 1820),
            $this->generic('modding', 'Phanteks Vertical GPU Bracket', 'phanteks-vertical-gpu-bracket', 'Phanteks', 'phanteks-vertical-gpu', 'Vertical GPU mount for showcase builds', ['color' => 'Black'], 1830),
            $this->generic('modding', 'Custom Side Panel Engraving', 'custom-side-panel-engraving', 'KondorPC', 'kondor-side-panel-engraving', 'Personalized side panel branding or logo', ['color' => 'Custom'], 1840),
            $this->generic('modding', 'Premium Cable Routing Pack', 'premium-cable-routing-pack', 'KondorPC', 'kondor-cable-routing-pack', 'Extra cable management and aesthetic routing work', ['color' => 'Custom'], 1850),

            $this->generic('other', 'Windows 11 Pro Activation', 'windows-11-pro-activation', 'Microsoft', 'windows-11-pro', 'Licensed Windows 11 Pro setup', ['color' => 'Digital'], 1910),
            $this->generic('other', 'Priority Assembly Queue', 'priority-assembly-queue', 'KondorPC', 'kondor-priority-assembly', 'Faster assembly and earlier dispatch slot', ['color' => 'Service'], 1920),
            $this->generic('other', 'Extended Stress Test Pack', 'extended-stress-test-pack', 'KondorPC', 'kondor-stress-test-pack', 'Longer burn-in and thermal validation', ['color' => 'Service'], 1930),
            $this->generic('other', 'BIOS Tuning & EXPO Setup', 'bios-tuning-and-expo-setup', 'KondorPC', 'kondor-bios-tuning-expo', 'Manual memory tuning and stability setup', ['color' => 'Service'], 1940),
            $this->generic('other', 'Premium Shipping Protection', 'premium-shipping-protection', 'KondorPC', 'kondor-shipping-protection', 'Extra packaging and transport protection', ['color' => 'Service'], 1950),
        ];
    }

    protected function cpu(
        string $sku,
        string $name,
        string $slug,
        string $vendor,
        string $socket,
        int $tdp,
        string $summary,
        array $meta,
        int $sortOrder,
    ): array {
        return [
            'type' => 'cpu',
            'name' => $name,
            'slug' => $slug,
            'vendor' => $vendor,
            'sku' => $sku,
            'summary' => $summary,
            'socket' => $socket,
            'cpu_tdp_w' => $tdp,
            'meta' => $meta,
            'sort_order' => $sortOrder,
            'is_active' => true,
        ];
    }

    protected function storage(
        string $sku,
        string $name,
        string $slug,
        string $vendor,
        string $article,
        string $summary,
        string $interface,
        int $capacityGb,
        array $meta,
        int $sortOrder,
    ): array {
        return [
            'type' => 'storage',
            'name' => $name,
            'slug' => $slug,
            'vendor' => $vendor,
            'sku' => $article,
            'summary' => $summary,
            'storage_interface' => $interface,
            'memory_capacity_gb' => $capacityGb,
            'meta' => $meta,
            'sort_order' => $sortOrder,
            'is_active' => true,
        ];
    }

    protected function pcCase(
        string $sku,
        string $name,
        string $slug,
        string $vendor,
        string $formFactor,
        int $gpuLimit,
        int $coolerLimit,
        array $supportedBoards,
        array $supportedRadiators,
        string $summary,
        array $meta,
        int $sortOrder,
    ): array {
        return [
            'type' => 'case',
            'name' => $name,
            'slug' => $slug,
            'vendor' => $vendor,
            'sku' => $sku,
            'summary' => $summary,
            'form_factor' => $formFactor,
            'supported_mb_form_factors' => $supportedBoards,
            'supported_radiator_sizes' => $supportedRadiators,
            'max_gpu_length_mm' => $gpuLimit,
            'max_cooler_height_mm' => $coolerLimit,
            'meta' => $meta,
            'sort_order' => $sortOrder,
            'is_active' => true,
        ];
    }

    protected function cooler(
        string $sku,
        string $name,
        string $slug,
        string $vendor,
        array $supportedSockets,
        int $heightMm,
        int $radiatorMm,
        string $summary,
        array $meta,
        int $sortOrder,
    ): array {
        return [
            'type' => 'cooler',
            'name' => $name,
            'slug' => $slug,
            'vendor' => $vendor,
            'sku' => $sku,
            'summary' => $summary,
            'supported_sockets' => $supportedSockets,
            'max_cooler_height_mm' => $heightMm > 0 ? $heightMm : null,
            'radiator_size_mm' => $radiatorMm > 0 ? $radiatorMm : null,
            'meta' => $meta,
            'sort_order' => $sortOrder,
            'is_active' => true,
        ];
    }

    protected function generic(
        string $type,
        string $name,
        string $slug,
        string $vendor,
        string $sku,
        string $summary,
        array $meta,
        int $sortOrder,
    ): array {
        return [
            'type' => $type,
            'name' => $name,
            'slug' => $slug,
            'vendor' => $vendor,
            'sku' => $sku,
            'summary' => $summary,
            'meta' => $meta,
            'sort_order' => $sortOrder,
            'is_active' => true,
        ];
    }

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
        if ($value === null) {
            return null;
        }

        return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
};
