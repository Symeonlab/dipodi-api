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
                Forms\Components\Section::make('Notification Content')
                    ->description('Compose your push notification message')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Notification Title')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., New workout available!')
                            ->helperText('Keep it short and engaging (max 50 chars recommended)'),
                        Forms\Components\Textarea::make('body')
                            ->label('Message Body')
                            ->required()
                            ->rows(4)
                            ->placeholder('Write your notification message here...')
                            ->helperText('The main content of your notification')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Scheduling & Targeting')
                    ->description('When and who should receive this notification')
                    ->icon('heroicon-o-clock')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'scheduled' => 'Scheduled',
                                'sent' => 'Sent',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('draft')
                            ->required()
                            ->native(false),
                        Forms\Components\DateTimePicker::make('scheduled_at')
                            ->label('Schedule For')
                            ->placeholder('Select date and time')
                            ->helperText('Leave empty to send immediately when status is changed to Scheduled')
                            ->seconds(false),
                        Forms\Components\DateTimePicker::make('sent_at')
                            ->label('Sent At')
                            ->disabled()
                            ->helperText('Automatically set when notification is sent'),
                        Forms\Components\Select::make('target_users')
                            ->label('Target Audience')
                            ->multiple()
                            ->options([
                                'all' => 'All Users',
                                'active' => 'Active Users (last 7 days)',
                                'inactive' => 'Inactive Users',
                                'onboarded' => 'Completed Onboarding',
                                'not_onboarded' => 'Not Completed Onboarding',
                                'football' => 'Football Players',
                                'fitness' => 'Fitness Users',
                                'padel' => 'Padel Players',
                            ])
                            ->default(['all'])
                            ->helperText('Select target user groups'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(40)
                    ->tooltip(function (PushNotification $record): string {
                        return $record->title;
                    }),
                Tables\Columns\TextColumn::make('body')
                    ->label('Message')
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
                    ->label('Audience')
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
                    ->label('Scheduled')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->placeholder('Not scheduled')
                    ->color(fn (PushNotification $record): string =>
                        $record->scheduled_at && $record->scheduled_at->isFuture() ? 'warning' : 'gray'
                    ),
                Tables\Columns\TextColumn::make('sent_at')
                    ->label('Sent')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->placeholder('Not sent yet')
                    ->color('success'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'scheduled' => 'Scheduled',
                        'sent' => 'Sent',
                        'cancelled' => 'Cancelled',
                    ])
                    ->multiple(),
                Tables\Filters\Filter::make('scheduled_future')
                    ->label('Upcoming Only')
                    ->query(fn (Builder $query): Builder => $query->where('scheduled_at', '>', now())),
                Tables\Filters\Filter::make('sent_today')
                    ->label('Sent Today')
                    ->query(fn (Builder $query): Builder => $query->whereDate('sent_at', today())),
            ])
            ->actions([
                Tables\Actions\Action::make('send')
                    ->label('Send Now')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Send Notification')
                    ->modalDescription('Are you sure you want to send this notification immediately?')
                    ->modalSubmitActionLabel('Yes, send it')
                    ->visible(fn (PushNotification $record): bool => in_array($record->status, ['draft', 'scheduled']))
                    ->action(function (PushNotification $record) {
                        $record->update([
                            'status' => 'sent',
                            'sent_at' => now(),
                        ]);
                    }),
                Tables\Actions\Action::make('duplicate')
                    ->label('Duplicate')
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
            ->emptyStateHeading('No notifications yet')
            ->emptyStateDescription('Create your first push notification to engage with users.')
            ->emptyStateIcon('heroicon-o-bell-alert');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Notification Content')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->schema([
                        Infolists\Components\TextEntry::make('title')
                            ->size('lg')
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('body')
                            ->columnSpanFull(),
                    ]),
                Infolists\Components\Section::make('Status & Scheduling')
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
                            ->label('Target Audience')
                            ->badge()
                            ->color('info'),
                        Infolists\Components\TextEntry::make('scheduled_at')
                            ->dateTime('F j, Y g:i A')
                            ->placeholder('Not scheduled'),
                        Infolists\Components\TextEntry::make('sent_at')
                            ->dateTime('F j, Y g:i A')
                            ->placeholder('Not sent yet'),
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
