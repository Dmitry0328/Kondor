<?php

namespace App\Filament\Resources\SharedBuildLinks;

use App\Filament\Resources\SharedBuildLinks\Pages\ListSharedBuildLinks;
use App\Filament\Resources\SharedBuildLinks\Pages\ViewSharedBuildLink;
use App\Models\SharedBuildLink;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SharedBuildLinkResource extends Resource
{
    protected static ?string $model = SharedBuildLink::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;

    public static function getNavigationGroup(): ?string
    {
        return 'Storefront';
    }

    public static function getNavigationLabel(): string
    {
        return 'Поділені збірки';
    }

    public static function getModelLabel(): string
    {
        return 'поділена збірка';
    }

    public static function getPluralModelLabel(): string
    {
        return 'поділені збірки';
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
        return $schema->components([]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Посилання')
                    ->schema([
                        TextEntry::make('build_name')
                            ->label('Збірка'),
                        TextEntry::make('build_slug')
                            ->label('Slug')
                            ->copyable(),
                        TextEntry::make('token')
                            ->label('Токен')
                            ->copyable(),
                        TextEntry::make('status_label')
                            ->label('Статус')
                            ->badge()
                            ->color(fn (SharedBuildLink $record): string => $record->is_expired ? 'danger' : 'success'),
                        TextEntry::make('selected_options_count')
                            ->label('Обрано опцій'),
                        TextEntry::make('payload.total_price')
                            ->label('Підсумкова ціна')
                            ->formatStateUsing(fn ($state): string => number_format((int) $state, 0, '', ' ') . ' ₴'),
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
                    ])
                    ->columns(3),
                Section::make('Конфігурація')
                    ->schema([
                        TextEntry::make('payload.summary')
                            ->label('Короткий опис')
                            ->formatStateUsing(function ($state): string {
                                if (! is_array($state) || $state === []) {
                                    return 'Без додаткових змін.';
                                }

                                return implode("\n", array_map('strval', $state));
                            })
                            ->columnSpanFull(),
                        TextEntry::make('payload.compatibility.is_valid')
                            ->label('Сумісність')
                            ->badge()
                            ->formatStateUsing(fn ($state): string => $state ? 'Сумісна' : 'Є зауваження')
                            ->color(fn ($state): string => $state ? 'success' : 'warning'),
                        TextEntry::make('payload.compatibility.messages')
                            ->label('Попередження')
                            ->formatStateUsing(function ($state): string {
                                if (! is_array($state) || $state === []) {
                                    return 'Без попереджень.';
                                }

                                return implode("\n", array_map('strval', $state));
                            })
                            ->columnSpanFull(),
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
                TextColumn::make('build_name')
                    ->label('Збірка')
                    ->searchable(),
                TextColumn::make('build_slug')
                    ->label('Slug')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('token')
                    ->label('Токен')
                    ->searchable()
                    ->copyable()
                    ->limit(14)
                    ->tooltip(fn (SharedBuildLink $record): string => $record->token),
                TextColumn::make('status_label')
                    ->label('Статус')
                    ->badge()
                    ->color(fn (SharedBuildLink $record): string => $record->is_expired ? 'danger' : 'success'),
                TextColumn::make('selected_options_count')
                    ->label('Опцій')
                    ->alignCenter(),
                TextColumn::make('payload.total_price')
                    ->label('Ціна')
                    ->formatStateUsing(fn ($state): string => number_format((int) $state, 0, '', ' ') . ' ₴'),
                TextColumn::make('shared_url')
                    ->label('Посилання')
                    ->copyable()
                    ->limit(42)
                    ->tooltip(fn (SharedBuildLink $record): string => $record->shared_url),
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

    public static function getPages(): array
    {
        return [
            'index' => ListSharedBuildLinks::route('/'),
            'view' => ViewSharedBuildLink::route('/{record}'),
        ];
    }
}
