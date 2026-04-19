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
                    ->description('Оберіть, як виглядає перший екран на головній сторінці.')
                    ->schema([
                        Radio::make('hero_mode')
                            ->label('Режим головного блока')
                            ->options([
                                'slider' => 'Новий слайдер',
                                'legacy' => 'Класичний блок',
                            ])
                            ->descriptions([
                                'slider' => 'Великий слайдер з кнопками та акцентною подачею.',
                                'legacy' => 'Спокійний варіант з текстом і статичним зображенням.',
                            ])
                            ->required()
                            ->default('slider')
                            ->inline(false),
                    ]),
                Section::make('Видимість блоків')
                    ->description('Керуйте секціями на головній і сторінках збірок. Дані не видаляються, блоки лише ховаються.')
                    ->schema([
                        Toggle::make('show_home_gallery')
                            ->label('Показувати блок "Наші роботи"')
                            ->default(true)
                            ->helperText('Фото зберігаються, навіть якщо блок тимчасово вимкнений.'),
                        Toggle::make('show_home_fps_lab')
                            ->label('Показувати FPS-блок')
                            ->default(true)
                            ->helperText('Керує великим FPS-блоком на головній сторінці.'),
                        Toggle::make('show_build_card_fps')
                            ->label('Показувати FPS у картках збірок')
                            ->default(true)
                            ->helperText('Керує шкалою FPS у картках збірок на сторінках вітрини.'),
                        Toggle::make('show_product_fps')
                            ->label('Показувати FPS на сторінці товару')
                            ->default(true)
                            ->helperText('Керує великим FPS-блоком на сторінці окремої збірки.'),
                        Toggle::make('show_build_compare')
                            ->label('Показувати порівняння збірок')
                            ->default(true)
                            ->helperText('Ховає кнопки порівняння, іконку в шапці та сторінку порівняння.'),
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
            ->title('Зміни збережено')
            ->body('Налаштування оформлення сайту оновлено.')
            ->success()
            ->send();
    }
}
