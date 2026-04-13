<?php

namespace App\Filament\Resources\ResolutionCards;

use App\Filament\Resources\ResolutionCards\Pages\ManageResolutionCards;
use App\Models\ResolutionCard;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ResolutionCardResource extends Resource
{
    protected static ?string $model = ResolutionCard::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?int $navigationSort = 5;

    public static function getNavigationGroup(): ?string
    {
        return 'Storefront';
    }

    public static function getNavigationLabel(): string
    {
        return 'Картки моніторів';
    }

    public static function getModelLabel(): string
    {
        return 'картка монітора';
    }

    public static function getPluralModelLabel(): string
    {
        return 'картки моніторів';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Картка для головної сторінки')
                ->description('Редагується блок "Оберіть збірку під свій монітор": назва, опис, акцент і фото.')
                ->schema([
                    TextInput::make('key')
                        ->label('Ключ')
                        ->required()
                        ->maxLength(255)
                        ->disabled(fn (?ResolutionCard $record): bool => $record !== null)
                        ->dehydrated(fn (?ResolutionCard $record): bool => $record === null)
                        ->helperText('Системний ключ. Для поточних карток: full-hd, full-hd-plus, 2k, 4k.'),
                    TextInput::make('label')
                        ->label('Назва')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('eyebrow')
                        ->label('Малий підпис')
                        ->maxLength(255),
                    Textarea::make('description')
                        ->label('Опис')
                        ->rows(4),
                    TextInput::make('accent_color')
                        ->label('Акцентний колір')
                        ->required()
                        ->maxLength(32)
                        ->helperText('HEX-колір, наприклад #8b5cf6'),
                    TextInput::make('button_label')
                        ->label('Текст кнопки')
                        ->required()
                        ->maxLength(255),
                    FileUpload::make('image_path')
                        ->label('Фото картки')
                        ->image()
                        ->disk('public')
                        ->directory('resolution-cards')
                        ->visibility('public')
                        ->imagePreviewHeight('180')
                        ->helperText('Якщо фото не завантажене, показується стилізована стандартна ілюстрація.'),
                    TextInput::make('sort_order')
                        ->label('Порядок')
                        ->required()
                        ->numeric()
                        ->default(0)
                        ->minValue(0),
                    Toggle::make('is_active')
                        ->label('Активна')
                        ->default(true),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable(),
                ImageColumn::make('image_path')
                    ->label('Фото')
                    ->disk('public')
                    ->square(),
                TextColumn::make('label')
                    ->label('Назва')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('key')
                    ->label('Ключ')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('accent_color')
                    ->label('Акцент')
                    ->badge(),
                IconColumn::make('is_active')
                    ->label('Активна')
                    ->boolean(),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageResolutionCards::route('/'),
        ];
    }
}
