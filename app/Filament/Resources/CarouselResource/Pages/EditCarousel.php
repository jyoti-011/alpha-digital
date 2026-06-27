<?php

namespace App\Filament\Resources\CarouselResource\Pages;

use App\Filament\Resources\CarouselResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCarousel extends EditRecord
{
    protected static string $resource = CarouselResource::class;

    public bool $hasUnsavedChangesAlert = true;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Back')
                ->icon('heroicon-o-arrow-left')
                ->url($this->getResource()::getUrl('index'))
                ->color('gray'),
            Actions\Action::make('preview')
                ->label('Preview')
                ->icon('heroicon-o-eye')
                ->url(fn () => url('/carousel/preview/' . $this->record->id))
                ->openUrlInNewTab(),
            Actions\ReplicateAction::make()
                ->label('Duplicate')
                ->icon('heroicon-o-document-duplicate')
                ->keyBindings(['alt+d'])
                ->excludeAttributes(['status', 'sort_order', 'start_date', 'end_date', 'created_at', 'updated_at'])
                ->mutateRecordDataUsing(function (array $data): array {
                    $data['status'] = 'draft';
                    if (isset($data['heading']) && $data['heading']) {
                        $data['heading'] = $data['heading'] . ' (Copy)';
                    }
                    return $data;
                })
                ->successNotificationTitle('Carousel duplicated successfully. The new slide has been saved as Draft.'),
            Actions\Action::make('save')
                ->label('Save Changes')
                ->icon('heroicon-o-check')
                ->action('save')
                ->keyBindings(['mod+s'])
                ->color('primary'),
            Actions\DeleteAction::make()
                ->outlined()
                ->color('danger'),
        ];
    }

    // Forces redirect back to the list
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}