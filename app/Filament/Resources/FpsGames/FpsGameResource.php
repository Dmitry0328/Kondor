<?php

namespace App\Filament\Resources\FpsGames;

use App\Filament\Clusters\FpsCluster;
use App\Filament\Resources\FpsGames\Pages\ManageFpsGames;
use App\Models\FpsGame;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FpsGameResource extends Resource
{
    protected static ?string $model = FpsGame::class;

    protected static ?string $cluster = FpsCluster::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedRocketLaunch;

    protected static ?int $navigationSort = 21;

    public static function getNavigationLabel(): string
    {
        return 'Ігри';
    }

    public static function getModelLabel(): string
    {
        return 'FPS-гра';
    }

    public static function getPluralModelLabel(): string
    {
        return 'FPS-ігри';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Параметри гри')
                ->schema([
                    TextInput::make('name')
                        ->label('Назва')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('key')
                        ->label('Ключ (ID)')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->helperText('Латиниця, без пробілів. Напр.: cyberpunk-2077'),
                    TextInput::make('badge')
                        ->label('Підпис (badge)')
                        ->maxLength(255),
                    TextInput::make('accent')
                        ->label('Accent колір')
                        ->maxLength(32)
                        ->placeholder('#f4dc39'),
                    TextInput::make('scene_from')
                        ->label('Scene from')
                        ->maxLength(32)
                        ->placeholder('#0f182f'),
                    TextInput::make('scene_to')
                        ->label('Scene to')
                        ->maxLength(32)
                        ->placeholder('#2b1211'),
                    TextInput::make('sort_order')
                        ->label('Порядок')
                        ->required()
                        ->numeric()
                        ->default(0)
                        ->minValue(0),
                    Toggle::make('is_default')
                        ->label('За замовчуванням')
                        ->default(false),
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
                TextColumn::make('name')
                    ->label('Назва')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('key')
                    ->label('ID')
                    ->searchable()
                    ->copyable(),
                IconColumn::make('is_default')
                    ->label('Default')
                    ->boolean(),
                IconColumn::make('is_active')
                    ->label('Активна')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Оновлено')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageFpsGames::route('/'),
        ];
    }
}
