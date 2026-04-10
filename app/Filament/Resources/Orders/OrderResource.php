<?php

namespace App\Filament\Resources\Orders;

use App\Filament\Resources\Orders\Pages\CreateOrder;
use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Filament\Resources\Orders\Pages\ViewOrder;
use App\Models\Order;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn as RepeatableTableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

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
        $count = static::getModel()::query()->where('status', Order::STATUS_NEW)->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function canCreate(): bool
    {
        return true;
    }

    public static function canEdit($record): bool
    {
        return true;
    }

    public static function canDelete($record): bool
    {
        return true;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make([
                'default' => 1,
                'xl' => 12,
            ])->schema([
                Group::make([
                    Section::make('Замовлення')
                        ->schema([
                            TextInput::make('number')
                                ->label('Номер замовлення')
                                ->maxLength(255)
                                ->unique(ignoreRecord: true)
                                ->placeholder('Буде згенеровано автоматично'),
                            Select::make('status')
                                ->label('Статус')
                                ->options(Order::statusOptions())
                                ->required()
                                ->native(false)
                                ->default(Order::STATUS_NEW),
                            DateTimePicker::make('ordered_at')
                                ->label('Дата замовлення')
                                ->required()
                                ->native(false)
                                ->seconds(false)
                                ->default(now()),
                            TextInput::make('shipping_ttn')
                                ->label('ТТН')
                                ->maxLength(255),
                            TextInput::make('total_amount')
                                ->label('Сума, грн')
                                ->numeric()
                                ->required()
                                ->minValue(0)
                                ->default(0),
                            Select::make('payment_method')
                                ->label('Оплата')
                                ->options([
                                    'cash_on_delivery' => 'Оплата при отриманні',
                                ])
                                ->required()
                                ->native(false)
                                ->default('cash_on_delivery'),
                        ])
                        ->columns(2),
                    Section::make('Клієнт')
                        ->schema([
                            TextInput::make('customer_name')
                                ->label("Ім'я та прізвище")
                                ->required()
                                ->maxLength(255),
                            TextInput::make('phone')
                                ->label('Телефон')
                                ->required()
                                ->tel()
                                ->maxLength(40),
                            TextInput::make('email')
                                ->label('Email')
                                ->email()
                                ->maxLength(255),
                            TextInput::make('messenger_contact')
                                ->label('Telegram / Viber')
                                ->maxLength(255),
                            Textarea::make('comment')
                                ->label('Коментар')
                                ->rows(4)
                                ->columnSpanFull(),
                        ])
                        ->columns(2),
                ])->columnSpan([
                    'default' => 1,
                    'xl' => 8,
                ]),
                Group::make([
                    Section::make('Доступ до відстеження')
                        ->schema([
                            TextInput::make('tracking_password')
                                ->label('Пароль відстеження')
                                ->required()
                                ->default(fn (): string => Order::generateTrackingPassword())
                                ->maxLength(64)
                                ->suffixAction(
                                    Action::make('regenerateTrackingPassword')
                                        ->label('Згенерувати')
                                        ->icon(Heroicon::OutlinedArrowPath)
                                        ->action(function (callable $set): void {
                                            $set('tracking_password', Order::generateTrackingPassword());
                                        })
                                )
                                ->helperText('Клієнт вводить цей пароль разом із номером замовлення та телефоном.'),
                            Placeholder::make('tracking_link')
                                ->label('Посилання для клієнта')
                                ->content(function (?Order $record): HtmlString|string {
                                    if (! $record?->number) {
                                        return 'З’явиться після збереження замовлення.';
                                    }

                                    $url = e($record->tracking_url);

                                    return new HtmlString('<a href="' . $url . '" target="_blank" rel="noreferrer" style="color:#2563eb;font-weight:700;">' . $url . '</a>');
                                }),
                            Placeholder::make('tracking_hint')
                                ->label('Що потрібно клієнту')
                                ->content('Номер замовлення, номер телефону і пароль. Без усіх трьох даних сторінка статусу не відкриється.'),
                        ]),
                ])->columnSpan([
                    'default' => 1,
                    'xl' => 8,
                ]),
            ]),
        ]);
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
                        TextEntry::make('ordered_at')
                            ->label('Дата замовлення')
                            ->dateTime('d.m.Y H:i'),
                        TextEntry::make('created_at')
                            ->label('Створено в системі')
                            ->dateTime('d.m.Y H:i'),
                        TextEntry::make('payment_method_label')
                            ->label('Оплата')
                            ->badge()
                            ->color('gray'),
                        TextEntry::make('shipping_ttn')
                            ->label('ТТН')
                            ->copyable()
                            ->placeholder('—'),
                        TextEntry::make('total_amount')
                            ->label('Сума')
                            ->formatStateUsing(fn ($state): string => number_format((int) $state, 0, '', ' ') . ' ₴'),
                        TextEntry::make('tracking_password')
                            ->label('Пароль відстеження')
                            ->copyable()
                            ->placeholder('—'),
                        TextEntry::make('tracking_url')
                            ->label('Сторінка відстеження')
                            ->copyable()
                            ->url(fn (Order $record): string => $record->tracking_url, shouldOpenInNewTab: true),
                    ])
                    ->columns(3),
                Section::make('Клієнт')
                    ->schema([
                        TextEntry::make('customer_name')
                            ->label("Ім'я та прізвище"),
                        TextEntry::make('phone')
                            ->label('Телефон')
                            ->copyable(),
                        TextEntry::make('email')
                            ->label('Email')
                            ->copyable()
                            ->placeholder('—'),
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
            ->defaultSort('ordered_at', 'desc')
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
                    ->label('Клієнт')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Телефон')
                    ->searchable(),
                TextColumn::make('shipping_ttn')
                    ->label('ТТН')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('total_amount')
                    ->label('Сума')
                    ->formatStateUsing(fn ($state): string => number_format((int) $state, 0, '', ' ') . ' ₴')
                    ->sortable(),
                TextColumn::make('ordered_at')
                    ->label('Дата замовлення')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Статус')
                    ->options(Order::statusOptions()),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                static::makeStatusAction(Order::STATUS_PROCESSING, 'В роботу', Heroicon::OutlinedClock, 'warning'),
                static::makeStatusAction(Order::STATUS_SHIPPED, 'Відправити', Heroicon::OutlinedTruck, 'primary'),
                static::makeStatusAction(Order::STATUS_COMPLETED, 'Завершити', Heroicon::OutlinedCheckCircle, 'success'),
                static::makeStatusAction(Order::STATUS_CANCELLED, 'Скасувати', Heroicon::OutlinedXCircle, 'danger'),
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
            'create' => CreateOrder::route('/create'),
            'view' => ViewOrder::route('/{record}'),
            'edit' => EditOrder::route('/{record}/edit'),
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
