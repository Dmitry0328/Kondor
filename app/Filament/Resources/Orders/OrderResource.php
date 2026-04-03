<?php

namespace App\Filament\Resources\Orders;

use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Filament\Resources\Orders\Pages\ViewOrder;
use App\Models\Order;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn as RepeatableTableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return 'Storefront';
    }

    public static function getNavigationLabel(): string
    {
        return 'Замовлення';
    }

    public static function getModelLabel(): string
    {
        return 'замовлення';
    }

    public static function getPluralModelLabel(): string
    {
        return 'замовлення';
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::query()->where('status', 'new')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return true;
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
                Section::make('Замовлення')
                    ->schema([
                        TextEntry::make('number')
                            ->label('Номер')
                            ->copyable()
                            ->placeholder('—'),
                        TextEntry::make('status_label')
                            ->label('Статус')
                            ->badge()
                            ->color(fn (Order $record): string => $record->status_color),
                        TextEntry::make('payment_method_label')
                            ->label('Оплата')
                            ->badge()
                            ->color('gray'),
                        TextEntry::make('total_amount')
                            ->label('Сума')
                            ->formatStateUsing(fn ($state): string => number_format((int) $state, 0, '', ' ') . ' ₴'),
                        TextEntry::make('created_at')
                            ->label('Створено')
                            ->dateTime('d.m.Y H:i'),
                        TextEntry::make('updated_at')
                            ->label('Оновлено')
                            ->dateTime('d.m.Y H:i'),
                    ])
                    ->columns(2),
                Section::make('Клієнт')
                    ->schema([
                        TextEntry::make('customer_name')
                            ->label("Ім'я та прізвище"),
                        TextEntry::make('phone')
                            ->label('Телефон')
                            ->copyable(),
                        TextEntry::make('messenger_contact')
                            ->label('Telegram / Viber')
                            ->copyable()
                            ->placeholder('—'),
                        TextEntry::make('comment')
                            ->label('Коментар')
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Позиції')
                    ->schema([
                        RepeatableEntry::make('items')
                            ->label('')
                            ->contained(false)
                            ->placeholder('У замовленні немає позицій.')
                            ->table([
                                RepeatableTableColumn::make('Збірка'),
                                RepeatableTableColumn::make('К-сть'),
                                RepeatableTableColumn::make('Ціна'),
                                RepeatableTableColumn::make('Сума'),
                            ])
                            ->schema([
                                TextEntry::make('build_name')
                                    ->label('Збірка')
                                    ->url(fn ($state, $record): ?string => $record['build_url'] ?? null, shouldOpenInNewTab: true),
                                TextEntry::make('quantity')
                                    ->label('К-сть'),
                                TextEntry::make('unit_price')
                                    ->label('Ціна')
                                    ->formatStateUsing(fn ($state): string => number_format((int) $state, 0, '', ' ') . ' ₴'),
                                TextEntry::make('line_total')
                                    ->label('Сума')
                                    ->formatStateUsing(fn ($state): string => number_format((int) $state, 0, '', ' ') . ' ₴'),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('number')
                    ->label('Номер')
                    ->searchable()
                    ->copyable()
                    ->sortable()
                    ->placeholder('—'),
                TextColumn::make('status_label')
                    ->label('Статус')
                    ->badge()
                    ->color(fn (Order $record): string => $record->status_color),
                TextColumn::make('customer_name')
                    ->label("Клієнт")
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Телефон')
                    ->searchable(),
                TextColumn::make('payment_method_label')
                    ->label('Оплата')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('total_amount')
                    ->label('Сума')
                    ->formatStateUsing(fn ($state): string => number_format((int) $state, 0, '', ' ') . ' ₴')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Створено')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Статус')
                    ->options([
                        'new' => 'Нове',
                        'processing' => 'В роботі',
                        'completed' => 'Завершене',
                        'cancelled' => 'Скасоване',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                DeleteAction::make(),
                static::makeStatusAction('processing', 'В роботу', Heroicon::OutlinedClock, 'warning'),
                static::makeStatusAction('completed', 'Завершити', Heroicon::OutlinedCheckCircle, 'success'),
                static::makeStatusAction('cancelled', 'Скасувати', Heroicon::OutlinedXCircle, 'danger'),
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
            'index' => ListOrders::route('/'),
            'view' => ViewOrder::route('/{record}'),
        ];
    }

    public static function makeStatusAction(string $status, string $label, Heroicon $icon, string $color): Action
    {
        return Action::make('setStatus' . ucfirst($status))
            ->label($label)
            ->icon($icon)
            ->color($color)
            ->requiresConfirmation()
            ->visible(fn (Order $record): bool => $record->status !== $status)
            ->action(fn (Order $record) => $record->update(['status' => $status]));
    }
}
