<?php

namespace App\Filament\Pages;

use App\Support\SiteSettings;
use BackedEnum;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class StorefrontAppearance extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPaintBrush;

    protected static ?string $navigationLabel = 'Оформлення сайту';

    protected static string|UnitEnum|null $navigationGroup = 'Storefront';

    protected static ?int $navigationSort = 997;

    protected string $view = 'filament.pages.storefront-appearance';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'hero_mode' => SiteSettings::string('home.hero.mode', 'slider'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Головний блок')
                    ->description('Оберіть, який варіант першого екрана показувати на головній сторінці.')
                    ->schema([
                        Radio::make('hero_mode')
                            ->label('Режим головного блока')
                            ->options([
                                'slider' => 'Новий анімований слайдер',
                                'legacy' => 'Стара версія hero-блока',
                            ])
                            ->descriptions([
                                'slider' => 'Темний слайдер у стилі референса з анімаціями, стрілками, крапками та світінням.',
                                'legacy' => 'Поточний статичний блок із текстом ліворуч і візуалом праворуч.',
                            ])
                            ->required()
                            ->default('slider')
                            ->inline(false),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        SiteSettings::set('home.hero.mode', (string) ($state['hero_mode'] ?? 'slider'));

        Notification::make()
            ->title('Налаштування збережено')
            ->body('Режим головного блока на сайті оновлено.')
            ->success()
            ->send();
    }
}
