<?php

namespace App\Filament\Resources\Articles\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;
use staabm\SideEffectsDetector\SideEffect;

class ArticleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                section::make('details')->schema([
                    TextEntry::make('title')
                        ->label('Title :')
                    ,
                    TextEntry::make('description')
                        ->label('Description :')
                    ,
                    ImageEntry::make('image')
                        ->label('Category Image :')
                        ->size(200) // حجم الصورة بالـ px
                        ->disk('public')
                        ->circular()
                        ->url(fn($record, $state) => filled($state)
                            ? (str_starts_with($state, 'http')
                                ? $state
                                : Storage::disk('public')->url($state))
                            : null
                        )
                        ->openUrlInNewTab()
                      ->alignCenter()                  ,
                ])->columnSpanFull(),
                Section::make('Paragraphs')
                    ->schema([
                        RepeatableEntry::make('paragraphs') // اسم العلاقة
                        ->label('Paragraphs List')


                            ->schema([
                             Section::make('Paragraph')->schema([
                                 TextEntry::make('order')
                                     ->label('Order :')->badge(),
                                 TextEntry::make('title')
                                     ->label('Title :'),

                                 TextEntry::make('body')
                                     ->label('description :')
                                     ->columnSpanFull()
                                 ,

                                 ImageEntry::make('image')
                                     ->label('Image :')
                                     ->size(120)
                                     ->disk('public')
                                     ->url(fn ($record, $state) => filled($state)
                                         ? (str_starts_with($state, 'http')
                                             ? $state
                                             : Storage::disk('public')->url($state))
                                         : null
                                     )
                                     ->openUrlInNewTab()
                                     ->alignCenter()
                                     ->columnSpanFull()

                             ])->columns(2)
                                 ->columnSpanFull(),
                    ])
                    ])
                    ->collapsible() // يخلي السيكشن قابل للطي
                    ->columnSpanFull()
            ]);

    }
}
