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
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationLabel = 'Posts';
    protected static ?int $navigationSort = 2;
    protected static ?string $recordTitleAttribute = 'title_en';

    public static function getGloballySearchableAttributes(): array
    {
        return ['title_en', 'title_fr', 'slug', 'user.name'];
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'Author' => $record->user?->name ?? 'Unknown',
            'Status' => $record->is_published ? 'Published' : 'Draft',
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
                        Forms\Components\Section::make('Post Content')
                            ->description('Write your post in multiple languages')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Forms\Components\Tabs::make('Translations')
                                    ->tabs([
                                        Forms\Components\Tabs\Tab::make('English')
                                            ->icon('heroicon-o-language')
                                            ->schema([
                                                Forms\Components\TextInput::make('title_en')
                                                    ->label('Title')
                                                    ->maxLength(255)
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug($state))),
                                                Forms\Components\RichEditor::make('content_en')
                                                    ->label('Content')
                                                    ->toolbarButtons([
                                                        'bold', 'italic', 'underline', 'strike',
                                                        'h2', 'h3', 'bulletList', 'orderedList',
                                                        'link', 'blockquote', 'redo', 'undo',
                                                    ]),
                                            ]),
                                        Forms\Components\Tabs\Tab::make('Francais')
                                            ->icon('heroicon-o-language')
                                            ->schema([
                                                Forms\Components\TextInput::make('title_fr')
                                                    ->label('Titre')
                                                    ->maxLength(255),
                                                Forms\Components\RichEditor::make('content_fr')
                                                    ->label('Contenu')
                                                    ->toolbarButtons([
                                                        'bold', 'italic', 'underline', 'strike',
                                                        'h2', 'h3', 'bulletList', 'orderedList',
                                                        'link', 'blockquote', 'redo', 'undo',
                                                    ]),
                                            ]),
                                        Forms\Components\Tabs\Tab::make('Arabic')
                                            ->icon('heroicon-o-language')
                                            ->schema([
                                                Forms\Components\TextInput::make('title_ar')
                                                    ->label('Title')
                                                    ->maxLength(255)
                                                    ->extraAttributes(['dir' => 'rtl']),
                                                Forms\Components\RichEditor::make('content_ar')
                                                    ->label('Content')
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
                        Forms\Components\Section::make('Settings')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->label('Author')
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->default(fn () => auth()->id()),
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->helperText('Auto-generated from title'),
                                Forms\Components\Toggle::make('is_published')
                                    ->label('Published')
                                    ->default(false)
                                    ->helperText('Make this post visible to users'),
                            ]),

                        Forms\Components\Section::make('Featured Image')
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
                    ->label('Image')
                    ->circular(),
                Tables\Columns\TextColumn::make('title_en')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(40),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Author')
                    ->badge()
                    ->color('gray')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-clock')
                    ->trueColor('success')
                    ->falseColor('warning'),
                Tables\Columns\TextColumn::make('translations')
                    ->label('Languages')
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
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Published')
                    ->placeholder('All Posts')
                    ->trueLabel('Published')
                    ->falseLabel('Drafts'),
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\Action::make('publish')
                    ->label('Publish')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(fn (Post $record) => $record->update(['is_published' => true]))
                    ->visible(fn (Post $record): bool => !$record->is_published)
                    ->requiresConfirmation(),
                Tables\Actions\Action::make('unpublish')
                    ->label('Unpublish')
                    ->icon('heroicon-o-x-mark')
                    ->color('warning')
                    ->action(fn (Post $record) => $record->update(['is_published' => false]))
                    ->visible(fn (Post $record): bool => $record->is_published)
                    ->requiresConfirmation(),
                Tables\Actions\Action::make('duplicate')
                    ->label('Duplicate')
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
                        ->label('Publish Selected')
                        ->icon('heroicon-o-check')
                        ->action(fn ($records) => $records->each->update(['is_published' => true]))
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('unpublish')
                        ->label('Unpublish Selected')
                        ->icon('heroicon-o-x-mark')
                        ->action(fn ($records) => $records->each->update(['is_published' => false]))
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('export')
                        ->label('Export Selected')
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
            ->emptyStateHeading('No posts yet')
            ->emptyStateDescription('Create your first post to engage with users.')
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
                Infolists\Components\Tabs::make('Content')
                    ->tabs([
                        Infolists\Components\Tabs\Tab::make('English')
                            ->schema([
                                Infolists\Components\TextEntry::make('title_en')
                                    ->label('Title')
                                    ->size('lg')
                                    ->weight('bold'),
                                Infolists\Components\TextEntry::make('content_en')
                                    ->label('Content')
                                    ->html(),
                            ]),
                        Infolists\Components\Tabs\Tab::make('Francais')
                            ->schema([
                                Infolists\Components\TextEntry::make('title_fr')
                                    ->label('Titre')
                                    ->size('lg')
                                    ->weight('bold'),
                                Infolists\Components\TextEntry::make('content_fr')
                                    ->label('Contenu')
                                    ->html(),
                            ]),
                        Infolists\Components\Tabs\Tab::make('Arabic')
                            ->schema([
                                Infolists\Components\TextEntry::make('title_ar')
                                    ->label('Title')
                                    ->size('lg')
                                    ->weight('bold')
                                    ->extraAttributes(['dir' => 'rtl']),
                                Infolists\Components\TextEntry::make('content_ar')
                                    ->label('Content')
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
