<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PostResource\Pages;
use App\Models\Post;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?int $navigationSort = 2;
    protected static ?string $recordTitleAttribute = 'title_en';

    public static function getNavigationGroup(): ?string
    {
        return __('filament.nav.content_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.posts');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.posts');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title_en', 'title_fr', 'slug', 'user.name'];
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            __('filament.labels.author') => $record->user?->name ?? 'Unknown',
            __('filament.labels.status') => $record->is_published ? __('filament.labels.published') : __('filament.labels.draft'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $unpublished = static::getModel()::where('is_published', false)->count();
        return $unpublished > 0 ? 'warning' : 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('filament.sections.post_content'))
                            ->description(__('filament.sections.post_content_desc'))
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Forms\Components\Tabs::make(__('filament.sections.translations'))
                                    ->tabs([
                                        Forms\Components\Tabs\Tab::make(__('filament.labels.english'))
                                            ->icon('heroicon-o-language')
                                            ->schema([
                                                Forms\Components\TextInput::make('title_en')
                                                    ->label(__('filament.labels.title'))
                                                    ->maxLength(255)
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug($state))),
                                                Forms\Components\RichEditor::make('content_en')
                                                    ->label(__('filament.labels.content'))
                                                    ->toolbarButtons([
                                                        'bold', 'italic', 'underline', 'strike',
                                                        'h2', 'h3', 'bulletList', 'orderedList',
                                                        'link', 'blockquote', 'redo', 'undo',
                                                    ]),
                                            ]),
                                        Forms\Components\Tabs\Tab::make(__('filament.labels.french'))
                                            ->icon('heroicon-o-language')
                                            ->schema([
                                                Forms\Components\TextInput::make('title_fr')
                                                    ->label(__('filament.labels.titre'))
                                                    ->maxLength(255),
                                                Forms\Components\RichEditor::make('content_fr')
                                                    ->label(__('filament.labels.contenu'))
                                                    ->toolbarButtons([
                                                        'bold', 'italic', 'underline', 'strike',
                                                        'h2', 'h3', 'bulletList', 'orderedList',
                                                        'link', 'blockquote', 'redo', 'undo',
                                                    ]),
                                            ]),
                                        Forms\Components\Tabs\Tab::make(__('filament.labels.arabic'))
                                            ->icon('heroicon-o-language')
                                            ->schema([
                                                Forms\Components\TextInput::make('title_ar')
                                                    ->label(__('filament.labels.title'))
                                                    ->maxLength(255)
                                                    ->extraAttributes(['dir' => 'rtl']),
                                                Forms\Components\RichEditor::make('content_ar')
                                                    ->label(__('filament.labels.content'))
                                                    ->extraAttributes(['dir' => 'rtl'])
                                                    ->toolbarButtons([
                                                        'bold', 'italic', 'underline', 'strike',
                                                        'h2', 'h3', 'bulletList', 'orderedList',
                                                        'link', 'blockquote', 'redo', 'undo',
                                                    ]),
                                            ]),
                                    ])
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('filament.sections.settings'))
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->label(__('filament.labels.author'))
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->default(fn () => auth()->id()),
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->helperText(__('filament.helper.slug_auto')),
                                Forms\Components\Toggle::make('is_published')
                                    ->label(__('filament.labels.published'))
                                    ->default(false)
                                    ->helperText(__('filament.helper.make_visible')),
                            ]),

                        Forms\Components\Section::make(__('filament.sections.featured_image'))
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Forms\Components\FileUpload::make('featured_image')
                                    ->label('')
                                    ->image()
                                    ->imageEditor()
                                    ->directory('posts')
                                    ->visibility('public'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label(__('filament.sections.featured_image'))
                    ->circular(),
                Tables\Columns\TextColumn::make('title_en')
                    ->label(__('filament.labels.title'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(40),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('filament.labels.author'))
                    ->badge()
                    ->color('gray')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_published')
                    ->label(__('filament.labels.status'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-clock')
                    ->trueColor('success')
                    ->falseColor('warning'),
                Tables\Columns\TextColumn::make('translations')
                    ->label(__('filament.labels.languages'))
                    ->getStateUsing(function (Post $record): string {
                        $langs = [];
                        if ($record->title_en) $langs[] = 'EN';
                        if ($record->title_fr) $langs[] = 'FR';
                        if ($record->title_ar) $langs[] = 'AR';
                        return implode(', ', $langs);
                    })
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.labels.created'))
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label(__('filament.filters.published'))
                    ->placeholder(__('filament.filters.all_posts'))
                    ->trueLabel(__('filament.labels.published'))
                    ->falseLabel(__('filament.filters.drafts')),
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\Action::make('publish')
                    ->label(__('filament.actions.publish'))
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(fn (Post $record) => $record->update(['is_published' => true]))
                    ->visible(fn (Post $record): bool => !$record->is_published)
                    ->requiresConfirmation(),
                Tables\Actions\Action::make('unpublish')
                    ->label(__('filament.actions.unpublish'))
                    ->icon('heroicon-o-x-mark')
                    ->color('warning')
                    ->action(fn (Post $record) => $record->update(['is_published' => false]))
                    ->visible(fn (Post $record): bool => $record->is_published)
                    ->requiresConfirmation(),
                Tables\Actions\Action::make('duplicate')
                    ->label(__('filament.actions.duplicate'))
                    ->icon('heroicon-o-document-duplicate')
                    ->color('gray')
                    ->action(function (Post $record) {
                        Post::create([
                            'title_en' => $record->title_en . ' (Copy)',
                            'title_fr' => $record->title_fr ? $record->title_fr . ' (Copie)' : null,
                            'title_ar' => $record->title_ar,
                            'content_en' => $record->content_en,
                            'content_fr' => $record->content_fr,
                            'content_ar' => $record->content_ar,
                            'slug' => Str::slug($record->title_en . '-copy-' . time()),
                            'user_id' => auth()->id(),
                            'is_published' => false,
                            'featured_image' => $record->featured_image,
                        ]);
                    }),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('publish')
                        ->label(__('filament.actions.publish_selected'))
                        ->icon('heroicon-o-check')
                        ->action(fn ($records) => $records->each->update(['is_published' => true]))
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('unpublish')
                        ->label(__('filament.actions.unpublish_selected'))
                        ->icon('heroicon-o-x-mark')
                        ->action(fn ($records) => $records->each->update(['is_published' => false]))
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('export')
                        ->label(__('filament.actions.export_selected'))
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function (\Illuminate\Support\Collection $records) {
                            $csv = "Title (EN),Title (FR),Author,Status,Slug,Created\n";
                            foreach ($records as $record) {
                                $title_en = str_replace('"', '""', $record->title_en ?? '');
                                $title_fr = str_replace('"', '""', $record->title_fr ?? '');
                                $status = $record->is_published ? 'Published' : 'Draft';
                                $csv .= "\"{$title_en}\",\"{$title_fr}\",\"{$record->user?->name}\",\"{$status}\",\"{$record->slug}\",\"{$record->created_at}\"\n";
                            }
                            return response()->streamDownload(fn () => print($csv), 'posts-export.csv');
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading(__('filament.empty.posts'))
            ->emptyStateDescription(__('filament.empty.posts_desc'))
            ->emptyStateIcon('heroicon-o-newspaper');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make()
                    ->schema([
                        Infolists\Components\ImageEntry::make('featured_image')
                            ->label('')
                            ->height(200),
                    ]),
                Infolists\Components\Tabs::make(__('filament.labels.content'))
                    ->tabs([
                        Infolists\Components\Tabs\Tab::make(__('filament.labels.english'))
                            ->schema([
                                Infolists\Components\TextEntry::make('title_en')
                                    ->label(__('filament.labels.title'))
                                    ->size('lg')
                                    ->weight('bold'),
                                Infolists\Components\TextEntry::make('content_en')
                                    ->label(__('filament.labels.content'))
                                    ->html(),
                            ]),
                        Infolists\Components\Tabs\Tab::make(__('filament.labels.french'))
                            ->schema([
                                Infolists\Components\TextEntry::make('title_fr')
                                    ->label(__('filament.labels.titre'))
                                    ->size('lg')
                                    ->weight('bold'),
                                Infolists\Components\TextEntry::make('content_fr')
                                    ->label(__('filament.labels.contenu'))
                                    ->html(),
                            ]),
                        Infolists\Components\Tabs\Tab::make(__('filament.labels.arabic'))
                            ->schema([
                                Infolists\Components\TextEntry::make('title_ar')
                                    ->label(__('filament.labels.title'))
                                    ->size('lg')
                                    ->weight('bold')
                                    ->extraAttributes(['dir' => 'rtl']),
                                Infolists\Components\TextEntry::make('content_ar')
                                    ->label(__('filament.labels.content'))
                                    ->html()
                                    ->extraAttributes(['dir' => 'rtl']),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'view' => Pages\ViewPost::route('/{record}'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
