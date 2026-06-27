<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarouselResource\Pages;
use App\Models\Carousel;
use Filament\Forms; // <-- ADDED THIS IMPORT
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables; // <-- ADDED THIS IMPORT FOR CLEANER CODE
use Filament\Tables\Table;

class CarouselResource extends Resource
{
    protected static ?string $model = Carousel::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?int $navigationSort = 8;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Carousel Details')
                    ->columnSpan('full')
                    ->tabs([
                                Forms\Components\Tabs\Tab::make('Images')
                                    ->schema([
                                        Forms\Components\FileUpload::make('image')
                                            ->label('Desktop Slide Image')
                                            ->image()
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/jpg'])
                                            ->maxSize(5120)
                                            ->rules(['dimensions:min_width=1920,min_height=700'])
                                            ->directory('carousels')
                                            ->required()
                                            ->helperText(new \Illuminate\Support\HtmlString('
                                                <ul class="text-xs text-gray-500 mt-1 list-disc list-inside">
                                                    <li><b>Aspect Ratio:</b> 16:9</li>
                                                    <li><b>Recommended:</b> 1920 &times; 700 px</li>
                                                    <li><b>Minimum:</b> 1920 &times; 700 px</li>
                                                    <li><b>Preferred:</b> WEBP</li>
                                                    <li><b>Maximum:</b> 5 MB</li>
                                                </ul>
                                                <div class="mt-3 text-xs text-gray-600 dark:text-gray-400">
                                                    <b>Safe Zone Guide:</b>
                                                    <div class="flex h-6 mt-1 rounded overflow-hidden text-center text-[10px] font-bold leading-6">
                                                        <div class="bg-red-200 text-red-700 w-1/4">Crop</div>
                                                        <div class="bg-green-200 text-green-700 w-2/4">Safe Zone (1200px)</div>
                                                        <div class="bg-red-200 text-red-700 w-1/4">Crop</div>
                                                    </div>
                                                </div>
                                            '))
                                            ->columnSpanFull(),

                                        Forms\Components\FileUpload::make('image_mobile')
                                            ->label('Mobile Slide Image (Optional)')
                                            ->image()
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/jpg'])
                                            ->maxSize(5120)
                                            ->rules(['dimensions:min_width=900,min_height=1200'])
                                            ->directory('carousels/mobile')
                                            ->helperText(new \Illuminate\Support\HtmlString('
                                                <ul class="text-xs text-gray-500 mt-1 list-disc list-inside">
                                                    <li><b>Aspect Ratio:</b> 4:5</li>
                                                    <li><b>Recommended:</b> 900 &times; 1200 px</li>
                                                    <li><b>Minimum:</b> 900 &times; 1200 px</li>
                                                    <li><b>Preferred:</b> WEBP</li>
                                                    <li><b>Maximum:</b> 5 MB</li>
                                                </ul>
                                            '))
                                            ->columnSpanFull(),
                                    ]),

                                Forms\Components\Tabs\Tab::make('Content')
                                    ->schema([
                                        Forms\Components\TextInput::make('collection_tag')
                                            ->label('Collection Tag')
                                            ->maxLength(30)
                                            ->extraInputAttributes(['maxlength' => 30])
                                            ->hint(fn ($component) => new \Illuminate\Support\HtmlString(
                                                '<span x-data="{ length: 0 }" ' .
                                                'x-init="setTimeout(() => { let el = document.getElementById(\'' . $component->getId() . '\'); if(el) length = el.value.length; }, 100)" ' .
                                                '@input.window="if($event.target.id === \'' . $component->getId() . '\') length = $event.target.value.length" ' .
                                                'x-text="length"></span> / 30'
                                            )),
                                            
                                        Forms\Components\TextInput::make('heading')
                                            ->label('Main Heading')
                                            ->maxLength(55)
                                            ->extraInputAttributes(['maxlength' => 55])
                                            ->hint(fn ($component) => new \Illuminate\Support\HtmlString(
                                                '<span x-data="{ length: 0 }" ' .
                                                'x-init="setTimeout(() => { let el = document.getElementById(\'' . $component->getId() . '\'); if(el) length = el.value.length; }, 100)" ' .
                                                '@input.window="if($event.target.id === \'' . $component->getId() . '\') length = $event.target.value.length" ' .
                                                'x-text="length"></span> / 55'
                                            )),
                                            
                                        Forms\Components\TextInput::make('sub_heading')
                                            ->label('Supporting Text')
                                            ->maxLength(120)
                                            ->extraInputAttributes(['maxlength' => 120])
                                            ->hint(fn ($component) => new \Illuminate\Support\HtmlString(
                                                '<span x-data="{ length: 0 }" ' .
                                                'x-init="setTimeout(() => { let el = document.getElementById(\'' . $component->getId() . '\'); if(el) length = el.value.length; }, 100)" ' .
                                                '@input.window="if($event.target.id === \'' . $component->getId() . '\') length = $event.target.value.length" ' .
                                                'x-text="length"></span> / 120'
                                            )),
                                    ])->columns(2),

                                Forms\Components\Tabs\Tab::make('Layout & Design')
                                    ->schema([
                                        Forms\Components\Section::make('Layout')
                                            ->schema([
                                                Forms\Components\Select::make('layout_settings.desktop_position')
                                                    ->label('Desktop Position')
                                                    ->options(['left' => 'Left', 'center' => 'Center', 'right' => 'Right'])
                                                    ->default('left'),
                                                Forms\Components\Select::make('layout_settings.mobile_position')
                                                    ->label('Mobile Position')
                                                    ->options(['left' => 'Left', 'center' => 'Center', 'right' => 'Right'])
                                                    ->default('center'),
                                                Forms\Components\Select::make('layout_settings.text_alignment')
                                                    ->label('Text Alignment')
                                                    ->options(['left' => 'Left', 'center' => 'Center', 'right' => 'Right'])
                                                    ->default('left'),
                                                Forms\Components\Select::make('layout_settings.image_focus_position')
                                                    ->label('Image Focus')
                                                    ->options(['left' => 'Left', 'center' => 'Center', 'right' => 'Right'])
                                                    ->default('center'),
                                            ])->columns(2),

                                        Forms\Components\Section::make('Advanced Design Settings')
                                            ->collapsed()
                                            ->schema([
                                                Forms\Components\Section::make('Overlay')
                                                    ->headerActions([
                                                        Forms\Components\Actions\Action::make('reset_overlay')
                                                            ->label('Reset')
                                                            ->action(fn (Forms\Set $set) => $set('design_settings.overlay.color', '#000000')
                                                                                        ->set('design_settings.overlay.opacity', 0.18)),
                                                    ])
                                                    ->schema([
                                                        Forms\Components\ColorPicker::make('design_settings.overlay.color')
                                                            ->label('Overlay Color')
                                                            ->default('#000000'),
                                                        Forms\Components\TextInput::make('design_settings.overlay.opacity')
                                                            ->label('Overlay Opacity')
                                                            ->numeric()
                                                            ->step(0.01)
                                                            ->default(0.18),
                                                    ])->columns(2),

                                                Forms\Components\Section::make('Typography')
                                                    ->headerActions([
                                                        Forms\Components\Actions\Action::make('reset_typography')
                                                            ->label('Reset')
                                                            ->action(fn (Forms\Set $set) => $set('layout_settings.heading_size', 72)
                                                                                        ->set('layout_settings.subtitle_size', 22)
                                                                                        ->set('layout_settings.tag_size', 16)),
                                                    ])
                                                    ->schema([
                                                        Forms\Components\TextInput::make('layout_settings.heading_size')
                                                            ->label('Heading Size')
                                                            ->numeric()
                                                            ->default(72),
                                                        Forms\Components\TextInput::make('layout_settings.subtitle_size')
                                                            ->label('Subtitle Size')
                                                            ->numeric()
                                                            ->default(22),
                                                        Forms\Components\TextInput::make('layout_settings.tag_size')
                                                            ->label('Tag Size')
                                                            ->numeric()
                                                            ->default(16),
                                                    ])->columns(3),

                                                Forms\Components\Section::make('Colors')
                                                    ->headerActions([
                                                        Forms\Components\Actions\Action::make('reset_colors')
                                                            ->label('Reset')
                                                            ->action(fn (Forms\Set $set) => $set('design_settings.text.heading_color', '#FFFFFF')
                                                                                        ->set('design_settings.text.body_color', '#F5F5F5')),
                                                    ])
                                                    ->schema([
                                                        Forms\Components\ColorPicker::make('design_settings.text.heading_color')
                                                            ->label('Heading')
                                                            ->default('#FFFFFF'),
                                                        Forms\Components\ColorPicker::make('design_settings.text.body_color')
                                                            ->label('Body')
                                                            ->default('#F5F5F5'),
                                                    ])->columns(2),
                                            ]),
                                    ]),
                                
                                Forms\Components\Tabs\Tab::make('Button')
                                    ->schema([
                                        Forms\Components\TextInput::make('button_text')
                                            ->label('Button Text')
                                            ->maxLength(20),
                                        Forms\Components\TextInput::make('button_link')
                                            ->label('Button URL')
                                            ->url(),
                                            
                                        Forms\Components\Section::make('Button Styling')
                                            ->headerActions([
                                                Forms\Components\Actions\Action::make('reset_button')
                                                    ->label('Reset')
                                                    ->action(function (Forms\Set $set) {
                                                        $set('design_settings.button.style', 'filled');
                                                        $set('design_settings.button.size', 'md');
                                                        $set('design_settings.button.bg', '#991b1b');
                                                        $set('design_settings.button.text', '#FFFFFF');
                                                        $set('design_settings.button.hover_color', '#7f1d1d');
                                                        $set('design_settings.button.border', null);
                                                        $set('design_settings.button.radius', 12);
                                                        $set('design_settings.button.width', 'auto');
                                                    })
                                            ])
                                            ->schema([
                                                Forms\Components\Select::make('design_settings.button.style')
                                                    ->label('Button Style')
                                                    ->options(['filled' => 'Filled', 'outline' => 'Outline'])
                                                    ->default('filled'),
                                                Forms\Components\Select::make('design_settings.button.size')
                                                    ->label('Button Size')
                                                    ->options(['sm' => 'Small', 'md' => 'Medium', 'lg' => 'Large'])
                                                    ->default('md'),
                                                Forms\Components\ColorPicker::make('design_settings.button.bg')
                                                    ->label('Background Color')
                                                    ->default('#991b1b'),
                                                Forms\Components\ColorPicker::make('design_settings.button.text')
                                                    ->label('Text Color')
                                                    ->default('#FFFFFF'),
                                                Forms\Components\ColorPicker::make('design_settings.button.hover_color')
                                                    ->label('Hover Background Color')
                                                    ->default('#7f1d1d'),
                                                Forms\Components\ColorPicker::make('design_settings.button.border')
                                                    ->label('Border Color'),
                                                Forms\Components\TextInput::make('design_settings.button.radius')
                                                    ->label('Border Radius (px)')
                                                    ->numeric()
                                                    ->default(12),
                                                Forms\Components\Select::make('design_settings.button.width')
                                                    ->label('Button Layout')
                                                    ->options(['auto' => 'Auto', 'full' => 'Full Width'])
                                                    ->default('auto'),
                                            ])->columns(2),
                                    ]),

                                Forms\Components\Tabs\Tab::make('Animation')
                                    ->schema([
                                        Forms\Components\Select::make('animation_settings.type')
                                            ->label('Text Animation')
                                            ->options(['fade-up' => 'Fade Up', 'fade-left' => 'Fade Left', 'fade-right' => 'Fade Right', 'none' => 'None'])
                                            ->default('fade-up'),
                                        Forms\Components\Toggle::make('animation_settings.ken_burns')
                                            ->label('Enable Ken Burns Effect (Slow Zoom)')
                                            ->default(true),
                                    ])->columns(2),
                                
                                Forms\Components\Tabs\Tab::make('Publishing & SEO')
                                    ->schema([
                                        Forms\Components\ToggleButtons::make('status')
                                            ->label('Status')
                                            ->options([
                                                'draft' => 'Draft',
                                                'published' => 'Published',
                                                'archived' => 'Archived',
                                            ])
                                            ->colors([
                                                'draft' => 'gray',
                                                'published' => 'success',
                                                'archived' => 'danger',
                                            ])
                                            ->icons([
                                                'draft' => 'heroicon-m-pencil',
                                                'published' => 'heroicon-m-check-circle',
                                                'archived' => 'heroicon-m-archive-box',
                                            ])
                                            ->inline()
                                            ->default('draft')
                                            ->required()
                                            ->helperText('Draft: Visible only inside Admin.'),
                                        Forms\Components\Placeholder::make('frontend_url')
                                            ->label('Frontend URL')
                                            ->content(fn ($record) => $record && $record->status === 'published' ? 'Homepage Hero | Slide #' . ($record->sort_order ?: '1') : 'Not visible on frontend')
                                            ->hidden(fn ($record) => !$record),
                                        Forms\Components\Toggle::make('pinned')
                                            ->label('Pinned (Always shows first)')
                                            ->default(false),
                                        Forms\Components\TextInput::make('sort_order')
                                            ->label('Display Order')
                                            ->numeric()
                                            ->default(0)
                                            ->helperText('Lower numbers show first'),
                                        Forms\Components\DateTimePicker::make('start_date')
                                            ->label('Start Date (Optional)'),
                                        Forms\Components\DateTimePicker::make('end_date')
                                            ->label('End Date (Optional)'),
                                        Forms\Components\TextInput::make('seo_alt_text')
                                            ->label('SEO Alt Text')
                                            ->columnSpanFull(),
                                     ])->columns(2),
                            ])
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\ImageColumn::make('image')
                    ->label('Slide Preview')
                    ->height('100px')
                    ->width('250px')
                    ->extraImgAttributes(['class' => 'object-cover rounded-lg shadow-sm'])
                    ->url(fn ($record) => asset('storage/' . $record->image))
                    ->openUrlInNewTab(),
                    
                \Filament\Tables\Columns\TextColumn::make('heading')
                    ->searchable()
                    ->weight('bold')
                    ->placeholder('No heading'),
                    
                \Filament\Tables\Columns\IconColumn::make('pinned')
                    ->boolean()
                    ->label('Pinned'),

                \Filament\Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                        'archived' => 'danger',
                    }),
                    
                \Filament\Tables\Columns\TextColumn::make('sort_order')
                    ->sortable()
                    ->label('Order'),
            ])
            ->defaultSort('sort_order', 'asc') 
            ->filters([
                //
            ])
            ->actions([
                \Filament\Tables\Actions\EditAction::make(),
                \Filament\Tables\Actions\ReplicateAction::make()
                    ->excludeAttributes(['status', 'sort_order', 'start_date', 'end_date', 'created_at', 'updated_at'])
                    ->mutateRecordDataUsing(function (array $data): array {
                        $data['status'] = 'draft';
                        if (isset($data['heading']) && $data['heading']) {
                            $data['heading'] = $data['heading'] . ' (Copy)';
                        }
                        return $data;
                    })
                    ->successNotificationTitle('Carousel duplicated successfully. The new slide has been saved as Draft.'),
                \Filament\Tables\Actions\DeleteAction::make()
                    ->outlined()
                    ->color('danger'),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\BulkActionGroup::make([
                    \Filament\Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCarousels::route('/'),
            'create' => Pages\CreateCarousel::route('/create'), // <-- RESTORED CREATE ROUTE
            'edit' => Pages\EditCarousel::route('/{record}/edit'), // <-- RESTORED EDIT ROUTE
        ];
    }
}