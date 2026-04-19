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

    protected static ?string $navigationLabel = 'Р В РЎвЂєР РЋРІР‚С›Р В РЎвЂўР РЋР вЂљР В РЎВР В Р’В»Р В Р’ВµР В Р вЂ¦Р В Р вЂ¦Р РЋР РЏ Р РЋР С“Р В Р’В°Р В РІвЂћвЂ“Р РЋРІР‚С™Р РЋРЎвЂњ';

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
                Section::make('Р“РѕР»РѕРІРЅРёР№ Р±Р»РѕРє')
                    ->description('РћР±РµСЂС–С‚СЊ, СЏРє РІРёРіР»СЏРґР°С” РїРµСЂС€РёР№ РµРєСЂР°РЅ РЅР° РіРѕР»РѕРІРЅС–Р№ СЃС‚РѕСЂС–РЅС†С–.')
                    ->schema([
                        Radio::make('hero_mode')
                            ->label('Р РµР¶РёРј РіРѕР»РѕРІРЅРѕРіРѕ Р±Р»РѕРєР°')
                            ->options([
                                'slider' => 'РќРѕРІРёР№ СЃР»Р°Р№РґРµСЂ',
                                'legacy' => 'РљР»Р°СЃРёС‡РЅРёР№ Р±Р»РѕРє',
                            ])
                            ->descriptions([
                                'slider' => 'Р’РµР»РёРєРёР№ СЃР»Р°Р№РґРµСЂ Р· РєРЅРѕРїРєР°РјРё С‚Р° Р°РєС†РµРЅС‚РЅРѕСЋ РІС–Р·СѓР°Р»СЊРЅРѕСЋ РїРѕРґР°С‡РµСЋ.',
                                'legacy' => 'РЎРїРѕРєС–Р№РЅРёР№ РІР°СЂС–Р°РЅС‚ Р· С‚РµРєСЃС‚РѕРј С– СЃС‚Р°С‚РёС‡РЅРёРј Р·РѕР±СЂР°Р¶РµРЅРЅСЏРј.',
                            ])
                            ->required()
                            ->default('slider')
                            ->inline(false),
                    ]),
                Section::make('Р В РІР‚ВР В Р’В»Р В РЎвЂўР В РЎвЂќР В РЎвЂ Р В РЎвЂ“Р В РЎвЂўР В Р’В»Р В РЎвЂўР В Р вЂ Р В Р вЂ¦Р В РЎвЂўР РЋРІР‚вЂќ')
                    ->description('Р В РЎС™Р В РЎвЂўР В Р’В¶Р В Р вЂ¦Р В Р’В° Р В РЎвЂ”Р РЋР вЂљР В РЎвЂР РЋРІР‚В¦Р В РЎвЂўР В Р вЂ Р РЋРЎвЂњР В Р вЂ Р В Р’В°Р РЋРІР‚С™Р В РЎвЂ Р В Р’В°Р В Р’В±Р В РЎвЂў Р В РЎвЂ”Р В РЎвЂўР В Р вЂ Р В Р’ВµР РЋР вЂљР РЋРІР‚С™Р В Р’В°Р РЋРІР‚С™Р В РЎвЂ Р РЋР С“Р В Р’ВµР В РЎвЂќР РЋРІР‚В Р РЋРІР‚вЂњР РЋРІР‚вЂќ Р В Р’В±Р В Р’ВµР В Р’В· Р В Р вЂ Р В РЎвЂР В РўвЂР В Р’В°Р В Р’В»Р В Р’ВµР В Р вЂ¦Р В Р вЂ¦Р РЋР РЏ Р РЋРІР‚С›Р В РЎвЂўР РЋРІР‚С™Р В РЎвЂў Р РЋРІР‚РЋР В РЎвЂ FPS-Р В РўвЂР В Р’В°Р В Р вЂ¦Р В РЎвЂР РЋРІР‚В¦.')
                    ->schema([
                        Toggle::make('show_home_gallery')
                            ->label('Р В РЎСџР В РЎвЂўР В РЎвЂќР В Р’В°Р В Р’В·Р РЋРЎвЂњР В Р вЂ Р В Р’В°Р РЋРІР‚С™Р В РЎвЂ Р В Р’В±Р В Р’В»Р В РЎвЂўР В РЎвЂќ "Р В РЎСљР В Р’В°Р РЋРІвЂљВ¬Р РЋРІР‚вЂњ Р РЋР вЂљР В РЎвЂўР В Р’В±Р В РЎвЂўР РЋРІР‚С™Р В РЎвЂ"')
                            ->default(true)
                            ->helperText('Р В Р’В¤Р В РЎвЂўР РЋРІР‚С™Р В РЎвЂў Р В Р’В·Р В Р’В°Р В Р’В»Р В РЎвЂР РЋРІвЂљВ¬Р В Р’В°Р РЋР вЂ№Р РЋРІР‚С™Р РЋР Р‰Р РЋР С“Р РЋР РЏ Р В Р’В·Р В Р’В±Р В Р’ВµР РЋР вЂљР В Р’ВµР В Р’В¶Р В Р’ВµР В Р вЂ¦Р В РЎвЂР В РЎВР В РЎвЂ, Р В Р вЂ¦Р В Р’В°Р В Р вЂ Р РЋРІР‚вЂњР РЋРІР‚С™Р РЋР Р‰ Р РЋР РЏР В РЎвЂќР РЋРІР‚В°Р В РЎвЂў Р В Р’В±Р В Р’В»Р В РЎвЂўР В РЎвЂќ Р РЋРІР‚С™Р В РЎвЂР В РЎВР РЋРІР‚РЋР В Р’В°Р РЋР С“Р В РЎвЂўР В Р вЂ Р В РЎвЂў Р В Р вЂ Р В РЎвЂР В РЎВР В РЎвЂќР В Р вЂ¦Р В Р’ВµР В Р вЂ¦Р В РЎвЂР В РІвЂћвЂ“.'),
                        Toggle::make('show_home_fps_lab')
                            ->label('Р В РЎСџР В РЎвЂўР В РЎвЂќР В Р’В°Р В Р’В·Р РЋРЎвЂњР В Р вЂ Р В Р’В°Р РЋРІР‚С™Р В РЎвЂ FPS-Р В Р’В±Р В Р’В»Р В РЎвЂўР В РЎвЂќ')
                            ->default(true)
                            ->helperText('FPS-Р В РўвЂР В Р’В°Р В Р вЂ¦Р РЋРІР‚вЂњ Р В Р’В»Р В РЎвЂР РЋРІвЂљВ¬Р В Р’В°Р РЋР вЂ№Р РЋРІР‚С™Р РЋР Р‰Р РЋР С“Р РЋР РЏ Р В Р вЂ  Р РЋР С“Р В РЎвЂР РЋР С“Р РЋРІР‚С™Р В Р’ВµР В РЎВР РЋРІР‚вЂњ, Р РЋР С“Р В Р’ВµР В РЎвЂќР РЋРІР‚В Р РЋРІР‚вЂњР РЋР РЏ Р РЋРІР‚С™Р РЋРІР‚вЂњР В Р’В»Р РЋР Р‰Р В РЎвЂќР В РЎвЂ Р РЋРІР‚В¦Р В РЎвЂўР В Р вЂ Р В Р’В°Р РЋРІР‚СњР РЋРІР‚С™Р РЋР Р‰Р РЋР С“Р РЋР РЏ Р В Р вЂ¦Р В Р’В° Р В РЎвЂ“Р В РЎвЂўР В Р’В»Р В РЎвЂўР В Р вЂ Р В Р вЂ¦Р РЋРІР‚вЂњР В РІвЂћвЂ“.'),
                        Toggle::make('show_build_card_fps')
                            ->label('Р В РЎСџР В РЎвЂўР В РЎвЂќР В Р’В°Р В Р’В·Р РЋРЎвЂњР В Р вЂ Р В Р’В°Р РЋРІР‚С™Р В РЎвЂ FPS Р РЋРЎвЂњ Р В РЎвЂќР В Р’В°Р РЋР вЂљР РЋРІР‚С™Р В РЎвЂќР В Р’В°Р РЋРІР‚В¦ Р В Р’В·Р В Р’В±Р РЋРІР‚вЂњР РЋР вЂљР В РЎвЂўР В РЎвЂќ')
                            ->default(true)
                            ->helperText('Р В РЎв„ўР В Р’ВµР РЋР вЂљР РЋРЎвЂњР РЋРІР‚Сњ FPS-Р РЋРІвЂљВ¬Р В РЎвЂќР В Р’В°Р В Р’В»Р В РЎвЂўР РЋР вЂ№ Р В Р вЂ  Р В РЎвЂќР В Р’В°Р РЋР вЂљР РЋРІР‚С™Р В РЎвЂќР В Р’В°Р РЋРІР‚В¦ Р В Р’В·Р В Р’В±Р РЋРІР‚вЂњР РЋР вЂљР В РЎвЂўР В РЎвЂќ Р В Р вЂ¦Р В Р’В° Р В Р вЂ Р РЋРІР‚вЂњР РЋРІР‚С™Р РЋР вЂљР В РЎвЂР В Р вЂ¦Р РЋРІР‚вЂњ.'),
                        Toggle::make('show_product_fps')
                            ->label('Р В РЎСџР В РЎвЂўР В РЎвЂќР В Р’В°Р В Р’В·Р РЋРЎвЂњР В Р вЂ Р В Р’В°Р РЋРІР‚С™Р В РЎвЂ FPS Р В Р вЂ¦Р В Р’В° Р РЋР С“Р РЋРІР‚С™Р В РЎвЂўР РЋР вЂљР РЋРІР‚вЂњР В Р вЂ¦Р РЋРІР‚В Р РЋРІР‚вЂњ Р РЋРІР‚С™Р В РЎвЂўР В Р вЂ Р В Р’В°Р РЋР вЂљР РЋРЎвЂњ')
                            ->default(true)
                            ->helperText('Р В РЎв„ўР В Р’ВµР РЋР вЂљР РЋРЎвЂњР РЋРІР‚Сњ Р В Р вЂ Р В Р’ВµР В Р’В»Р В РЎвЂР В РЎвЂќР В РЎвЂР В РЎВ FPS-Р В Р’В±Р В Р’В»Р В РЎвЂўР В РЎвЂќР В РЎвЂўР В РЎВ Р В Р вЂ¦Р В Р’В° Р РЋР С“Р РЋРІР‚С™Р В РЎвЂўР РЋР вЂљР РЋРІР‚вЂњР В Р вЂ¦Р РЋРІР‚В Р РЋРІР‚вЂњ Р В РЎвЂќР В РЎвЂўР В Р вЂ¦Р В РЎвЂќР РЋР вЂљР В Р’ВµР РЋРІР‚С™Р В Р вЂ¦Р В РЎвЂўР РЋРІР‚вЂќ Р В Р’В·Р В Р’В±Р РЋРІР‚вЂњР РЋР вЂљР В РЎвЂќР В РЎвЂ.'),
                        Toggle::make('show_build_compare')
                            ->label('Р В РЎСџР В РЎвЂўР В РЎвЂќР В Р’В°Р В Р’В·Р РЋРЎвЂњР В Р вЂ Р В Р’В°Р РЋРІР‚С™Р В РЎвЂ Р В РЎвЂ”Р В РЎвЂўР РЋР вЂљР РЋРІР‚вЂњР В Р вЂ Р В Р вЂ¦Р РЋР РЏР В Р вЂ¦Р В Р вЂ¦Р РЋР РЏ Р В Р’В·Р В Р’В±Р РЋРІР‚вЂњР РЋР вЂљР В РЎвЂўР В РЎвЂќ')
                            ->default(true)
                            ->helperText('Р В РІР‚в„ўР В РЎВР В РЎвЂР В РЎвЂќР В Р’В°Р РЋРІР‚Сњ Р В Р’В°Р В Р’В±Р В РЎвЂў Р В Р вЂ Р В РЎвЂР В РЎВР В РЎвЂР В РЎвЂќР В Р’В°Р РЋРІР‚Сњ Р В РЎвЂќР В Р вЂ¦Р В РЎвЂўР В РЎвЂ”Р В РЎвЂќР В РЎвЂ, Р РЋРІР‚вЂњР В РЎвЂќР В РЎвЂўР В Р вЂ¦Р В РЎвЂќР РЋРЎвЂњ Р В Р вЂ  Р РЋРІвЂљВ¬Р В Р’В°Р В РЎвЂ”Р РЋРІР‚В Р РЋРІР‚вЂњ Р РЋРІР‚С™Р В Р’В° Р РЋР С“Р РЋРІР‚С™Р В РЎвЂўР РЋР вЂљР РЋРІР‚вЂњР В Р вЂ¦Р В РЎвЂќР РЋРЎвЂњ Р В РЎвЂ”Р В РЎвЂўР РЋР вЂљР РЋРІР‚вЂњР В Р вЂ Р В Р вЂ¦Р РЋР РЏР В Р вЂ¦Р В Р вЂ¦Р РЋР РЏ.'),
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
            ->title('Р В РЎСљР В Р’В°Р В Р’В»Р В Р’В°Р РЋРІвЂљВ¬Р РЋРІР‚С™Р РЋРЎвЂњР В Р вЂ Р В Р’В°Р В Р вЂ¦Р В Р вЂ¦Р РЋР РЏ Р В Р’В·Р В Р’В±Р В Р’ВµР РЋР вЂљР В Р’ВµР В Р’В¶Р В Р’ВµР В Р вЂ¦Р В РЎвЂў')
            ->body('Р В РЎвЂєР РЋРІР‚С›Р В РЎвЂўР РЋР вЂљР В РЎВР В Р’В»Р В Р’ВµР В Р вЂ¦Р В Р вЂ¦Р РЋР РЏ Р В РЎвЂ“Р В РЎвЂўР В Р’В»Р В РЎвЂўР В Р вЂ Р В Р вЂ¦Р В РЎвЂўР РЋРІР‚вЂќ Р РЋР С“Р РЋРІР‚С™Р В РЎвЂўР РЋР вЂљР РЋРІР‚вЂњР В Р вЂ¦Р В РЎвЂќР В РЎвЂ Р В РЎвЂўР В Р вЂ¦Р В РЎвЂўР В Р вЂ Р В Р’В»Р В Р’ВµР В Р вЂ¦Р В РЎвЂў.')
            ->success()
            ->send();
    }
}
