<?php

namespace App\Filament\Resources\Projects\projects\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class projectInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('details')
                    ->description('project details .')
                    ->schema([
                        TextEntry::make('name')->label('Name :')->badge(),         // قصير = badge OK
                        TextEntry::make('location')->label('Location :')->badge(), // قصير = badge OK
                        TextEntry::make('date')->label('Date :')->badge(),         // قصير = badge OK
                        TextEntry::make('contractor')->label('Contractor :')->badge(),
                        TextEntry::make('category.name')->label('Project Type :')->badge(),

                        // الوصف: بلا badge، مع تنسيق نص وتفاف أسطر وأخذ سطر كامل
                        TextEntry::make('main_description')
                            ->label('Main Description :')
                            ->columnSpanFull()
                            ->extraAttributes([
                                'class' => 'break-words whitespace-pre-line', // لفّ الأسطر واحترم \n
                            ]),

                        TextEntry::make('second_description')
                            ->label('Second Description :')
                            ->columnSpanFull()
                            ->extraAttributes([
                                'class' => 'break-words whitespace-pre-line', // لفّ الأسطر واحترم \n
                            ]),
                        TextEntry::make('third_description')
                            ->label('Third Description :')
                            ->columnSpanFull()
                            ->extraAttributes([
                                'class' => 'break-words whitespace-pre-line', // لفّ الأسطر واحترم \n
                            ]),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
                Section::make('Details')
                    ->schema([
                        RepeatableEntry::make('details')
                            ->label('Details')
                            ->schema([
                                TextEntry::make('title')
                                    ->label('Title')
                                    ->weight('medium')
                                ->badge(),
                                TextEntry::make('text')
                                    ->label('Text')
                                    ->prose()->badge(), // تنسيق نص لطيف
                            ])->columns(2)// ترتيب العناصر

                    ])->columnSpanFull(),

                Section::make('Main Image :')
                    ->schema([
                        ImageEntry::make('mainImage')
                        ->disk('public')
                            ->label('Image')
                            ->size(150)
                            ->url(fn ($record, $state) => filled($state)
                                ? (str_starts_with($state, 'http')
                                    ? $state
                                    : Storage::disk('public')->url($state))
                                : null
                            )
                            ->openUrlInNewTab()
                            ->extraAttributes(['class' => 'cursor-zoom-in']) //
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),


                Section::make('Interior Images')
                    ->schema([
                        ImageEntry::make('interior_images')   // انتبه للاسم: interior_images مو interiorImages
                        ->disk('public')
                            ->label('Interior')
                            ->size(150)
                            ->url(fn ($record, $state) => filled($state)
                                ? (str_starts_with($state, 'http')
                                    ? $state
                                    : Storage::disk('public')->url($state))
                                : null
                            )
                            ->openUrlInNewTab()
                            ->extraAttributes(['class' => 'cursor-zoom-in'])
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->columnSpanFull(),

                Section::make('Exterior Images')
                    ->schema([
                        ImageEntry::make('exterior_images')
                            ->disk('public')
                            ->label('Exterior')
                            ->size(150)
                            ->url(fn ($record, $state) => filled($state)
                                ? (str_starts_with($state, 'http')
                                    ? $state
                                    : Storage::disk('public')->url($state))
                                : null
                            )
                            ->openUrlInNewTab()
                            ->extraAttributes(['class' => 'cursor-zoom-in'])
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->columnSpanFull(),
            ]);
    }
}
