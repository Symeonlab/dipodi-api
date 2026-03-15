<?php

namespace App\Filament\Admin\Resources\HealthAssessmentCategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';
    protected static ?string $title = 'Questions';
    protected static ?string $icon = 'heroicon-o-question-mark-circle';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament.sections.question_content'))
                    ->schema([
                        Forms\Components\Textarea::make('question_fr')
                            ->label(__('filament.labels.french_question'))
                            ->required()
                            ->rows(2)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('question_en')
                            ->label(__('filament.labels.english_question'))
                            ->required()
                            ->rows(2)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('question_ar')
                            ->label(__('filament.labels.arabic_question'))
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make(__('filament.sections.answer_config'))
                    ->schema([
                        Forms\Components\Select::make('answer_type')
                            ->label(__('filament.labels.answer_type'))
                            ->required()
                            ->options([
                                'yes_no' => __('filament.answer_types.boolean'),
                                'scale' => __('filament.answer_types.scale_1_10'),
                                'text' => __('filament.answer_types.text'),
                                'multiple_choice' => __('filament.answer_types.multiple_choice'),
                            ])
                            ->live(),
                        Forms\Components\KeyValue::make('answer_options')
                            ->label(__('filament.labels.answer_options'))
                            ->keyLabel(__('filament.labels.value'))
                            ->valueLabel(__('filament.labels.label'))
                            ->helperText(__('filament.helper.multiple_choice'))
                            ->visible(fn ($get) => $get('answer_type') === 'multiple_choice')
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_critical')
                            ->label(__('filament.labels.critical_question'))
                            ->helperText(__('filament.helper.critical_health'))
                            ->default(false),
                        Forms\Components\Toggle::make('is_active')
                            ->label(__('filament.labels.active'))
                            ->default(true),
                        Forms\Components\TextInput::make('sort_order')
                            ->label(__('filament.labels.sort_order'))
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('question_en')
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')
                    ->label(__('filament.labels.order'))
                    ->sortable()
                    ->width('50px'),
                Tables\Columns\TextColumn::make('question_en')
                    ->label(__('filament.labels.question_en'))
                    ->searchable()
                    ->limit(60)
                    ->wrap(),
                Tables\Columns\TextColumn::make('question_fr')
                    ->label(__('filament.labels.question_fr'))
                    ->searchable()
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('answer_type')
                    ->label(__('filament.labels.type'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'yes_no' => 'success',
                        'scale' => 'info',
                        'text' => 'warning',
                        'multiple_choice' => 'purple',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_critical')
                    ->label(__('filament.labels.critical'))
                    ->boolean()
                    ->trueIcon('heroicon-o-exclamation-triangle')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('danger'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('filament.labels.active'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('answers_count')
                    ->label(__('filament.labels.responses'))
                    ->counts('answers')
                    ->badge()
                    ->color('gray'),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('answer_type')
                    ->options([
                        'yes_no' => __('filament.answer_types.boolean'),
                        'scale' => __('filament.answer_types.scale_1_10'),
                        'text' => __('filament.answer_types.text'),
                        'multiple_choice' => __('filament.answer_types.multiple_choice'),
                    ]),
                Tables\Filters\TernaryFilter::make('is_critical')
                    ->label(__('filament.labels.critical')),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('filament.labels.active')),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('sort_order')
            ->emptyStateHeading(__('filament.empty.questions'))
            ->emptyStateDescription(__('filament.empty.questions_health_desc'))
            ->emptyStateIcon('heroicon-o-question-mark-circle');
    }
}
