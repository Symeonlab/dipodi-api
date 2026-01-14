<?php
namespace App\Filament\Resources;
use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->default(auth()->id())
                    ->required(),
                Forms\Components\Toggle::make('is_published')
                    ->label('Published')
                    ->required(),
                Forms\Components\FileUpload::make('featured_image')
                    ->image()->columnSpanFull(),

                Forms\Components\Tabs::make('Translations')->tabs([
                    Forms\Components\Tabs\Tab::make('English')
                        ->schema([
                            Forms\Components\TextInput::make('title_en')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                            Forms\Components\RichEditor::make('content_en')
                                ->required()->columnSpanFull(),
                        ]),
                    Forms\Components\Tabs\Tab::make('Français')
                        ->schema([
                            Forms\Components\TextInput::make('title_fr')->maxLength(255),
                            Forms\Components\RichEditor::make('content_fr')->columnSpanFull(),
                        ]),
                    Forms\Components\Tabs\Tab::make('العربية')
                        ->schema([
                            Forms\Components\TextInput::make('title_ar')->maxLength(255),
                            Forms\Components\RichEditor::make('content_ar')->columnSpanFull(),
                        ]),
                ])->columnSpanFull(),

                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(Post::class, 'slug', ignoreRecord: true),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title_en')->label('Title (EN)')->searchable(),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
                Tables\Columns\TextColumn::make('user.name')->label('Author')->sortable(),
            ])
            ->actions([Tables\Actions\EditAction::make()]);
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
