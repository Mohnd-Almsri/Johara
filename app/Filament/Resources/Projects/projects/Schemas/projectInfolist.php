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
                Section::make('details')->description('project details .')->schema([
                    TextEntry::make('name')->label('Name :')->badge(),
                    TextEntry::make('location')->label('Location :')->badge(),
                    TextEntry::make('date')->label('Date :')->badge(),
                    TextEntry::make('contractor')->label('Contractor :')->badge(),
                    TextEntry::make('description')->label('Description :')->badge(),
                    TextEntry::make('category.name')->label('Category :')->badge(),
                    TextEntry::make('created_at')->badge()
                        ->label('added to website at : ')
                        ->dateTime(),
                ])->columns(3)->columnSpanFull(),
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
