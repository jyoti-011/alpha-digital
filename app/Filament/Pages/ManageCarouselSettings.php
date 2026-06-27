<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Actions\Action;

class ManageCarouselSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?string $navigationLabel = 'Carousel Settings';
    protected static ?string $title = 'Global Carousel Settings';
    protected static string $view = 'filament.pages.manage-carousel-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $setting = Setting::getSiteSettings();
        $this->form->fill($setting->carousel_settings ?? [
            'autoplay_speed' => 6500,
            'transition_speed' => 700,
            'infinite_loop' => true,
            'pause_on_hover' => true,
            'show_pagination' => true,
            'show_navigation' => true,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Swiper.js Settings')
                    ->description('These settings apply to the entire homepage hero carousel.')
                    ->schema([
                        Forms\Components\TextInput::make('autoplay_speed')
                            ->label('Autoplay Delay (ms)')
                            ->numeric()
                            ->required()
                            ->default(6500)
                            ->hint('Time between slides (e.g. 6500 = 6.5s)'),
                        Forms\Components\TextInput::make('transition_speed')
                            ->label('Transition Duration (ms)')
                            ->numeric()
                            ->required()
                            ->default(700)
                            ->hint('Time it takes to crossfade to the next slide (e.g. 700 = 0.7s)'),
                        Forms\Components\Toggle::make('infinite_loop')
                            ->label('Infinite Loop')
                            ->default(true),
                        Forms\Components\Toggle::make('pause_on_hover')
                            ->label('Pause on Hover')
                            ->default(true),
                        Forms\Components\Toggle::make('show_pagination')
                            ->label('Show Pagination (Dots)')
                            ->default(true),
                        Forms\Components\Toggle::make('show_navigation')
                            ->label('Show Navigation (Arrows)')
                            ->default(true),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    public function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->submit('save')
                ->keyBindings(['mod+s']),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $setting = Setting::getSiteSettings();
        
        $setting->update([
            'carousel_settings' => $data,
        ]);

        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();
    }
}
