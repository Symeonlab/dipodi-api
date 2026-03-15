<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PushNotificationResource\Pages;
use App\Models\PushNotification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PushNotificationResource extends Resource
{
    protected static ?string $model = PushNotification::class;
    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'title';

    public static function getNavigationGroup(): ?string
    {
        return __('filament.nav.communication');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.push_notifications');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.push_notifications');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'scheduled')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament.sections.notification_content'))
                    ->description(__('filament.sections.notification_content_desc'))
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('filament.labels.notification_title'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('filament.placeholders.notification_title'))
                            ->helperText(__('filament.helper.keep_short')),
                        Forms\Components\Textarea::make('body')
                            ->label(__('filament.labels.message_body'))
                            ->required()
                            ->rows(4)
                            ->placeholder(__('filament.placeholders.notification_body'))
                            ->helperText(__('filament.helper.notification_body'))
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make(__('filament.sections.scheduling_targeting'))
                    ->description(__('filament.sections.scheduling_targeting_desc'))
                    ->icon('heroicon-o-clock')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label(__('filament.labels.status'))
                            ->options([
                                'draft' => __('filament.notification_status.draft'),
                                'scheduled' => __('filament.notification_status.scheduled'),
                                'sent' => __('filament.notification_status.sent'),
                                'cancelled' => __('filament.notification_status.cancelled'),
                            ])
                            ->default('draft')
                            ->required()
                            ->native(false),
                        Forms\Components\DateTimePicker::make('scheduled_at')
                            ->label(__('filament.labels.schedule_for'))
                            ->placeholder(__('filament.placeholders.select_datetime'))
                            ->helperText(__('filament.helper.schedule_empty'))
                            ->seconds(false),
                        Forms\Components\DateTimePicker::make('sent_at')
                            ->label(__('filament.labels.sent_at'))
                            ->disabled()
                            ->helperText(__('filament.helper.sent_auto')),
                        Forms\Components\Select::make('target_users')
                            ->label(__('filament.labels.target_audience'))
                            ->multiple()
                            ->options([
                                'all' => __('filament.target_audiences.all'),
                                'active' => __('filament.target_audiences.active'),
                                'inactive' => __('filament.target_audiences.inactive'),
                                'onboarded' => __('filament.target_audiences.onboarded'),
                                'not_onboarded' => __('filament.target_audiences.not_onboarded'),
                                'football' => __('filament.target_audiences.football'),
                                'fitness' => __('filament.target_audiences.fitness'),
                                'padel' => __('filament.target_audiences.padel'),
                            ])
                            ->default(['all'])
                            ->helperText(__('filament.helper.select_target')),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('filament.labels.title'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(40)
                    ->tooltip(function (PushNotification $record): string {
                        return $record->title;
                    }),
                Tables\Columns\TextColumn::make('body')
                    ->label(__('filament.labels.message'))
                    ->limit(50)
                    ->toggleable()
                    ->tooltip(function (PushNotification $record): string {
                        return $record->body;
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'scheduled' => 'warning',
                        'sent' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'draft' => 'heroicon-o-pencil',
                        'scheduled' => 'heroicon-o-clock',
                        'sent' => 'heroicon-o-check-circle',
                        'cancelled' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('target_users')
                    ->label(__('filament.labels.audience'))
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(function ($state) {
                        if (is_array($state)) {
                            return implode(', ', array_map('ucfirst', $state));
                        }
                        return $state;
                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label(__('filament.labels.scheduled'))
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->placeholder(__('filament.placeholders.not_scheduled'))
                    ->color(fn (PushNotification $record): string =>
                        $record->scheduled_at && $record->scheduled_at->isFuture() ? 'warning' : 'gray'
                    ),
                Tables\Columns\TextColumn::make('sent_at')
                    ->label(__('filament.labels.sent'))
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->placeholder(__('filament.placeholders.not_sent'))
                    ->color('success'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.labels.created'))
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => __('filament.notification_status.draft'),
                        'scheduled' => __('filament.notification_status.scheduled'),
                        'sent' => __('filament.notification_status.sent'),
                        'cancelled' => __('filament.notification_status.cancelled'),
                    ])
                    ->multiple(),
                Tables\Filters\Filter::make('scheduled_future')
                    ->label(__('filament.labels.upcoming_only'))
                    ->query(fn (Builder $query): Builder => $query->where('scheduled_at', '>', now())),
                Tables\Filters\Filter::make('sent_today')
                    ->label(__('filament.labels.sent_today'))
                    ->query(fn (Builder $query): Builder => $query->whereDate('sent_at', today())),
            ])
            ->actions([
                Tables\Actions\Action::make('send')
                    ->label(__('filament.actions.send_now'))
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading(__('filament.actions.send_notification'))
                    ->modalDescription(__('filament.actions.confirm_send'))
                    ->modalSubmitActionLabel(__('filament.actions.yes_send'))
                    ->visible(fn (PushNotification $record): bool => in_array($record->status, ['draft', 'scheduled']))
                    ->action(function (PushNotification $record) {
                        $record->update([
                            'status' => 'sent',
                            'sent_at' => now(),
                        ]);
                    }),
                Tables\Actions\Action::make('duplicate')
                    ->label(__('filament.actions.duplicate'))
                    ->icon('heroicon-o-document-duplicate')
                    ->color('gray')
                    ->action(function (PushNotification $record) {
                        PushNotification::create([
                            'title' => $record->title . ' (Copy)',
                            'body' => $record->body,
                            'status' => 'draft',
                            'target_users' => $record->target_users,
                        ]);
                    }),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading(__('filament.empty.notifications'))
            ->emptyStateDescription(__('filament.empty.notifications_desc'))
            ->emptyStateIcon('heroicon-o-bell-alert');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(__('filament.sections.notification_content'))
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->schema([
                        Infolists\Components\TextEntry::make('title')
                            ->size('lg')
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('body')
                            ->columnSpanFull(),
                    ]),
                Infolists\Components\Section::make(__('filament.sections.status_scheduling'))
                    ->icon('heroicon-o-clock')
                    ->schema([
                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'draft' => 'gray',
                                'scheduled' => 'warning',
                                'sent' => 'success',
                                'cancelled' => 'danger',
                                default => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('target_users')
                            ->label(__('filament.labels.target_audience'))
                            ->badge()
                            ->color('info'),
                        Infolists\Components\TextEntry::make('scheduled_at')
                            ->dateTime('F j, Y g:i A')
                            ->placeholder(__('filament.placeholders.not_scheduled')),
                        Infolists\Components\TextEntry::make('sent_at')
                            ->dateTime('F j, Y g:i A')
                            ->placeholder(__('filament.placeholders.not_sent')),
                    ])
                    ->columns(2),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPushNotifications::route('/'),
            'create' => Pages\CreatePushNotification::route('/create'),
            'view' => Pages\ViewPushNotification::route('/{record}'),
            'edit' => Pages\EditPushNotification::route('/{record}/edit'),
        ];
    }
}
