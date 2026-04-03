<?php

namespace App\Filament\Resources\Components\Pages;

use App\Filament\Resources\Components\ComponentResource;
use App\Models\Component;
use App\Support\BuildConfigurator;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\RenderHook;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\View\PanelsRenderHook;
use Illuminate\Database\Eloquent\Builder;

class ManageComponents extends ManageRecords
{
    protected static string $resource = ComponentResource::class;

    protected static ?string $breadcrumb = 'Список';

    protected function getHeaderActions(): array
    {
        return [
            ComponentResource::makeCreateAction(),
        ];
    }

    public function hasResourceBreadcrumbs(): bool
    {
        return true;
    }

    public function getSubNavigation(): array
    {
        return [];
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make([
                'default' => 1,
                'xl' => 12,
                '2xl' => 12,
            ])->schema([
                View::make('filament.resources.components.pages.type-sidebar')
                    ->viewData(fn (): array => [
                        'items' => $this->getSidebarItems(),
                    ])
                    ->columnSpan([
                        'default' => 1,
                        'xl' => 4,
                        '2xl' => 3,
                    ]),
                Group::make([
                    RenderHook::make(PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_BEFORE),
                    EmbeddedTable::make(),
                    RenderHook::make(PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_AFTER),
                ])->columnSpan([
                    'default' => 1,
                    'xl' => 8,
                    '2xl' => 9,
                ]),
            ]),
        ]);
    }

    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make('Усі компоненти')
                ->icon(BuildConfigurator::componentTypeIcon('all'))
                ->badge((string) Component::query()->count()),
        ];

        foreach (BuildConfigurator::componentTypeOptions() as $type => $label) {
            $count = Component::query()->where('type', $type)->count();

            $tabs[$type] = Tab::make($label)
                ->icon(BuildConfigurator::componentTypeIcon($type))
                ->badge((string) $count)
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('type', $type));
        }

        return $tabs;
    }

    protected function getSidebarItems(): array
    {
        $activeTab = (string) ($this->activeTab ?? $this->getDefaultActiveTab());

        return collect($this->getCachedTabs())
            ->map(function (Tab $tab, string | int $key) use ($activeTab): array {
                $tabKey = (string) $key;

                return [
                    'key' => $tabKey,
                    'label' => $tab->getLabel() ?? $this->generateTabLabel($tabKey),
                    'icon' => $tab->getIcon(),
                    'badge' => $tab->getBadge(),
                    'is_active' => $tabKey === $activeTab,
                ];
            })
            ->values()
            ->all();
    }
}
