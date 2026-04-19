<?php

namespace App\Filament\Pages;

use App\Support\SiteSettings;
use BackedEnum;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
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
            'show_home_gallery' => SiteSettings::bool('home.gallery.enabled', true),
            'show_home_fps_lab' => SiteSettings::bool('home.fps_lab.enabled', true),
            'show_build_card_fps' => SiteSettings::bool('build.cards.fps.enabled', true),
            'show_product_fps' => SiteSettings::bool('build.product.fps.enabled', true),
            'show_build_compare' => SiteSettings::bool('build.compare.enabled', true),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Головний блок')
                    ->description('Обери, який варіант першого екрана показувати на головній сторінці.')
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
                Section::make('Блоки головної сторінки')
                    ->description('Увімкни або приховай окремі секції на головній без видалення фото чи FPS-даних.')
                    ->schema([
                        Toggle::make('show_home_gallery')
                            ->label('Показувати блок "Наші роботи"')
                            ->default(true)
                            ->helperText('Фото залишаються збереженими, навіть якщо блок тимчасово вимкнений.'),
                        Toggle::make('show_home_fps_lab')
                            ->label('Показувати FPS-блок')
                            ->default(true)
                            ->helperText('FPS-дані збірок не видаляються, секція лише ховається на головній сторінці.'),
                        Toggle::make('show_build_card_fps')
                            ->label('Показувати FPS у картках збірок')
                            ->default(true)
                            ->helperText('Керує FPS-шкалою в картках збірок на вітрині. На головній також ховається разом із вимкненим FPS-блоком.'),
                        Toggle::make('show_product_fps')
                            ->label('Показувати FPS на сторінці товару')
                            ->default(true)
                            ->helperText('Керує великим FPS-блоком на сторінці конкретної збірки.'),
                        Toggle::make('show_build_compare')
                            ->label('Показувати порівняння збірок')
                            ->default(true)
                            ->helperText('Повністю вмикає або вимикає функцію порівняння: кнопки, іконку в шапці та сторінку порівняння.'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        SiteSettings::set('home.hero.mode', (string) ($state['hero_mode'] ?? 'slider'));
        SiteSettings::set('home.gallery.enabled', ! empty($state['show_home_gallery']) ? '1' : '0');
        SiteSettings::set('home.fps_lab.enabled', ! empty($state['show_home_fps_lab']) ? '1' : '0');
        SiteSettings::set('build.cards.fps.enabled', ! empty($state['show_build_card_fps']) ? '1' : '0');
        SiteSettings::set('build.product.fps.enabled', ! empty($state['show_product_fps']) ? '1' : '0');
        SiteSettings::set('build.compare.enabled', ! empty($state['show_build_compare']) ? '1' : '0');

        Notification::make()
            ->title('Налаштування збережено')
            ->body('Оформлення головної сторінки оновлено.')
            ->success()
            ->send();
    }
}
