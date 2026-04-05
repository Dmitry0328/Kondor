<?php

namespace App\Filament\Resources\TradeInRequests;

use App\Filament\Resources\TradeInRequests\Pages\ListTradeInRequests;
use App\Filament\Resources\TradeInRequests\Pages\ViewTradeInRequest;
use App\Models\TradeInRequest;
use BackedEnum;
use Filament\Actions\Action;
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
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class TradeInRequestResource extends Resource
{
    protected static ?string $model = TradeInRequest::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedComputerDesktop;

    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return 'Storefront';
    }

    public static function getNavigationLabel(): string
    {
        return 'Трейд-ін заявки';
    }

    public static function getModelLabel(): string
    {
        return 'заявка трейд-іну';
    }

    public static function getPluralModelLabel(): string
    {
        return 'заявки трейд-іну';
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

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Заявка')
                    ->schema([
                        TextEntry::make('id')
                            ->label('ID')
                            ->copyable(),
                        TextEntry::make('target_build_label')
                            ->label('Цільова збірка'),
                        TextEntry::make('build_slug')
                            ->label('Slug збірки')
                            ->copyable()
                            ->placeholder('—'),
                        TextEntry::make('status_label')
                            ->label('Статус')
                            ->badge()
                            ->color(fn (TradeInRequest $record): string => $record->status_color),
                        TextEntry::make('photos_count')
                            ->label('Фото'),
                        TextEntry::make('created_at')
                            ->label('Створено')
                            ->dateTime('d.m.Y H:i'),
                    ])
                    ->columns(3),
                Section::make('Клієнт')
                    ->schema([
                        TextEntry::make('customer_name')
                            ->label("Ім'я"),
                        TextEntry::make('phone')
                            ->label('Телефон')
                            ->copyable(),
                        TextEntry::make('messenger_contact')
                            ->label('Telegram / Viber')
                            ->copyable()
                            ->placeholder('—'),
                        TextEntry::make('description')
                            ->label('Опис ПК')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Цільова збірка')
                    ->schema([
                        TextEntry::make('build_snapshot')
                            ->label('Деталі конфігурації')
                            ->state(fn (TradeInRequest $record): string => static::renderBuildSnapshot($record)->toHtml())
                            ->html()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Фото')
                    ->schema([
                        TextEntry::make('photo_paths')
                            ->label('Галерея')
                            ->state(fn (TradeInRequest $record): string => static::renderPhotoGallery($record)->toHtml())
                            ->html()
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
                ViewColumn::make('preview')
                    ->label('Фото')
                    ->view('filament.tables.columns.admin-image-preview')
                    ->viewData(fn (TradeInRequest $record): array => [
                        'imageUrl' => $record->primaryPhotoUrl(),
                        'placeholderUrl' => $record->placeholderUrl(),
                        'hasImage' => $record->hasPhotos(),
                        'caption' => 'Trade-in #' . $record->getKey(),
                        'alt' => $record->target_build_label,
                    ]),
                TextColumn::make('target_build_label')
                    ->label('Збірка')
                    ->searchable(['build_name', 'build_slug'])
                    ->sortable(query: fn ($query, string $direction) => $query->orderBy('build_name', $direction))
                    ->url(fn (TradeInRequest $record): ?string => $record->snapshotSharedUrl() ?? $record->snapshotBuildUrl(), shouldOpenInNewTab: true),
                TextColumn::make('customer_name')
                    ->label('Клієнт')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Телефон')
                    ->searchable(),
                TextColumn::make('photos_count')
                    ->label('Фото')
                    ->alignCenter(),
                TextColumn::make('status_label')
                    ->label('Статус')
                    ->badge()
                    ->color(fn (TradeInRequest $record): string => $record->status_color),
                TextColumn::make('created_at')
                    ->label('Створено')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Статус')
                    ->options([
                        'new' => 'Нова',
                        'processing' => 'В роботі',
                        'completed' => 'Закрито',
                        'rejected' => 'Відхилено',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                DeleteAction::make(),
                static::makeStatusAction('processing', 'В роботу', Heroicon::OutlinedClock, 'warning'),
                static::makeStatusAction('completed', 'Закрити', Heroicon::OutlinedCheckCircle, 'success'),
                static::makeStatusAction('rejected', 'Відхилити', Heroicon::OutlinedXCircle, 'danger'),
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
            'index' => ListTradeInRequests::route('/'),
            'view' => ViewTradeInRequest::route('/{record}'),
        ];
    }

    public static function makeStatusAction(string $status, string $label, Heroicon $icon, string $color): Action
    {
        return Action::make('setStatus' . ucfirst($status))
            ->label($label)
            ->icon($icon)
            ->color($color)
            ->requiresConfirmation()
            ->visible(fn (TradeInRequest $record): bool => $record->status !== $status)
            ->action(fn (TradeInRequest $record) => $record->update(['status' => $status]));
    }

    protected static function renderPhotoGallery(TradeInRequest $record): HtmlString
    {
        $urls = $record->photoUrls();

        if ($urls === []) {
            return new HtmlString('<span style="color:#64748b;">Фото не додано.</span>');
        }

        $items = array_map(function (string $url, int $index): string {
            $safeUrl = e($url);
            $title = 'Фото ' . ($index + 1);

            return <<<HTML
<a href="{$safeUrl}" target="_blank" rel="noreferrer" style="display:block;overflow:hidden;border:1px solid #d7deea;border-radius:18px;background:#fff;box-shadow:0 10px 22px rgba(15,23,42,.08);">
  <img src="{$safeUrl}" alt="{$title}" style="display:block;width:100%;height:180px;object-fit:cover;background:#eef3fb;">
</a>
HTML;
        }, $urls, array_keys($urls));

        return new HtmlString(
            '<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:12px;">'
            . implode('', $items)
            . '</div>'
        );
    }

    protected static function renderBuildSnapshot(TradeInRequest $record): HtmlString
    {
        $detailLines = $record->buildDetailLines();
        $links = [];

        if ($record->snapshotSharedUrl()) {
            $safeUrl = e((string) $record->snapshotSharedUrl());
            $links[] = <<<HTML
<a href="{$safeUrl}" target="_blank" rel="noreferrer" style="display:inline-flex;align-items:center;justify-content:center;min-height:38px;padding:0 14px;border:1px solid #d7deea;border-radius:999px;background:#fff;color:#18202a;font-size:13px;font-weight:800;text-decoration:none;">Відкрити точну конфігурацію</a>
HTML;
        } elseif ($record->snapshotBuildUrl()) {
            $safeUrl = e((string) $record->snapshotBuildUrl());
            $links[] = <<<HTML
<a href="{$safeUrl}" target="_blank" rel="noreferrer" style="display:inline-flex;align-items:center;justify-content:center;min-height:38px;padding:0 14px;border:1px solid #d7deea;border-radius:999px;background:#fff;color:#18202a;font-size:13px;font-weight:800;text-decoration:none;">Відкрити збірку на сайті</a>
HTML;
        }

        if ($record->snapshotAdditionalPrice() > 0) {
            $links[] = '<span style="display:inline-flex;align-items:center;justify-content:center;min-height:38px;padding:0 14px;border:1px solid #d7deea;border-radius:999px;background:#fff;color:#18202a;font-size:13px;font-weight:800;">Додаткові опції: +' . e(number_format($record->snapshotAdditionalPrice(), 0, '.', ' ')) . ' грн</span>';
        }

        if ($record->snapshotTotalPrice() !== null) {
            $links[] = '<span style="display:inline-flex;align-items:center;justify-content:center;min-height:38px;padding:0 14px;border:1px solid #d7deea;border-radius:999px;background:#fff;color:#18202a;font-size:13px;font-weight:800;">Разом: ' . e(number_format((int) $record->snapshotTotalPrice(), 0, '.', ' ')) . ' грн</span>';
        }

        if ($detailLines === [] && $links === []) {
            return new HtmlString('<span style="color:#64748b;">Деталі збірки не передані.</span>');
        }

        $detailsHtml = $detailLines === []
            ? '<span style="color:#64748b;">Без кастомних змін. Якщо збірку не змінювали через додаткові опції, тут буде показана базова збірка.</span>'
            : '<ul style="display:grid;gap:8px;margin:0;padding-left:18px;color:#475569;font-size:14px;font-weight:700;line-height:1.55;">'
                . implode('', array_map(static fn (string $line): string => '<li>' . e($line) . '</li>', $detailLines))
                . '</ul>';

        return new HtmlString(
            '<div style="display:grid;gap:14px;">'
            . $detailsHtml
            . ($links === [] ? '' : '<div style="display:flex;flex-wrap:wrap;gap:10px;">' . implode('', $links) . '</div>')
            . '</div>'
        );
    }
}
