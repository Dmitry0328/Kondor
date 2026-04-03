<?php

namespace App\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ConfiguratorCluster extends Cluster
{
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedCpuChip;

    protected static ?string $navigationLabel = 'Комплектуючі';

    protected static string | UnitEnum | null $navigationGroup = 'Storefront';

    protected static ?int $navigationSort = 15;

    public static function getClusterBreadcrumb(): ?string
    {
        return 'Комплектуючі';
    }
}
