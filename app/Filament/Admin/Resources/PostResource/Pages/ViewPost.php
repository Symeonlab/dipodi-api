<?php

namespace App\Filament\Admin\Resources\PostResource\Pages;

use App\Filament\Admin\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPost extends ViewRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('publish')
                ->label(__('filament.actions.publish'))
                ->icon('heroicon-o-check')
                ->color('success')
                ->action(fn () => $this->record->update(['is_published' => true]))
                ->visible(fn (): bool => !$this->record->is_published)
                ->requiresConfirmation(),
            Actions\Action::make('unpublish')
                ->label(__('filament.actions.unpublish'))
                ->icon('heroicon-o-x-mark')
                ->color('warning')
                ->action(fn () => $this->record->update(['is_published' => false]))
                ->visible(fn (): bool => $this->record->is_published)
                ->requiresConfirmation(),
            Actions\EditAction::make(),
        ];
    }
}
