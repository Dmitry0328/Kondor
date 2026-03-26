<?php

namespace App\Filament\Resources\SharedCarts;

use App\Filament\Resources\SharedCarts\Pages\ListSharedCarts;
use App\Filament\Resources\SharedCarts\Pages\ViewSharedCart;
use App\Models\SharedCart;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn as RepeatableTableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SharedCartResource extends Resource
{
    protected static ?string $model = SharedCart::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getNavigationGroup(): ?string
    {
        return 'Storefront';
    }

    public static function getNavigationLabel(): string
    {
        return 'Поділені кошики';
    }

    public static function getModelLabel(): string
    {
        return 'поділений кошик';
    }

    public static function getPluralModelLabel(): string
    {
        return 'поділені кошики';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Посилання')
                    ->schema([
                        TextEntry::make('token')
                            ->label('Токен')
                            ->copyable(),
                        TextEntry::make('status_label')
                            ->label('Статус')
                            ->badge()
                            ->color(fn (SharedCart $record): string => $record->is_expired ? 'danger' : 'success'),
                        TextEntry::make('shared_url')
                            ->label('Посилання')
                            ->copyable()
                            ->columnSpanFull(),
                        TextEntry::make('expires_at')
                            ->label('Активне до')
                            ->dateTime('d.m.Y H:i'),
                        TextEntry::make('created_at')
                            ->label('Створено')
                            ->dateTime('d.m.Y H:i'),
                        TextEntry::make('items_count')
                            ->label('Позицій'),
                    ])
                    ->columns(3),
                Section::make('Вміст кошика')
                    ->schema([
                        RepeatableEntry::make('payload')
                            ->label('')
                            ->contained(false)
                            ->placeholder('У кошику немає позицій.')
                            ->table([
                                RepeatableTableColumn::make('Збірка'),
                                RepeatableTableColumn::make('Slug'),
                                RepeatableTableColumn::make('К-сть'),
                                RepeatableTableColumn::make('Ціна'),
                                RepeatableTableColumn::make('Сума'),
                            ])
                            ->schema([
                                TextEntry::make('name')->label('Збірка'),
                                TextEntry::make('slug')->label('Slug'),
                                TextEntry::make('quantity')->label('К-сть'),
                                TextEntry::make('price')
                                    ->label('Ціна')
                                    ->formatStateUsing(fn ($state): string => number_format((int) $state, 0, '', ' ') . ' ₴'),
                                TextEntry::make('line_total')
                                    ->label('Сума')
                                    ->formatStateUsing(fn ($state): string => number_format((int) $state, 0, '', ' ') . ' ₴'),
                            ]),
                        TextEntry::make('payload')
                            ->label('JSON payload')
                            ->formatStateUsing(static fn ($state): string => json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '[]')
                            ->copyable()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('token')
                    ->label('Токен')
                    ->searchable()
                    ->copyable()
                    ->limit(14)
                    ->tooltip(fn (SharedCart $record): string => $record->token),
                TextColumn::make('status_label')
                    ->label('Статус')
                    ->badge()
                    ->color(fn (SharedCart $record): string => $record->is_expired ? 'danger' : 'success'),
                TextColumn::make('items_count')
                    ->label('Позицій')
                    ->alignCenter(),
                TextColumn::make('shared_url')
                    ->label('Посилання')
                    ->copyable()
                    ->limit(42)
                    ->tooltip(fn (SharedCart $record): string => $record->shared_url),
                TextColumn::make('expires_at')
                    ->label('Активне до')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Створено')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('activity')
                    ->label('Стан')
                    ->options([
                        'active' => 'Активні',
                        'expired' => 'Прострочені',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return match ($data['value'] ?? null) {
                            'active' => $query->where('expires_at', '>', now()),
                            'expired' => $query->where('expires_at', '<=', now()),
                            default => $query,
                        };
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSharedCarts::route('/'),
            'view' => ViewSharedCart::route('/{record}'),
        ];
    }
}
