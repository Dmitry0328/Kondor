<?php

namespace App\Filament\Resources\FpsPresets;

use App\Filament\Clusters\FpsCluster;
use App\Filament\Resources\FpsPresets\Pages\ManageFpsPresets;
use App\Models\FpsPreset;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
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

class FpsPresetResource extends Resource
{
    protected static ?string $model = FpsPreset::class;

    protected static ?string $cluster = FpsCluster::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;

    protected static ?int $navigationSort = 23;

    public static function getNavigationLabel(): string
    {
        return 'Графіка';
    }

    public static function getModelLabel(): string
    {
        return 'FPS-пресет';
    }

    public static function getPluralModelLabel(): string
    {
        return 'FPS-пресети';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Параметри графіки')
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
                        ->helperText('Латиниця, без пробілів. Напр.: high'),
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
                        ->label('Активний')
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
                    ->label('Активний')
                    ->boolean(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageFpsPresets::route('/'),
        ];
    }
}
