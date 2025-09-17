<?php

namespace App\Filament\Resources\Ceos\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class CeoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                section::make('Details :')->schema([
                    textEntry::make('name')->label('Name : '),
                    textEntry::make('paragraph_1')->label('Paragraph 1 : '),
                    textEntry::make('paragraph_2')->label('Paragraph 2 : '),
                    textEntry::make('paragraph_3')->label('Paragraph 3 : '),
                ])->columns(1)->columnSpanFull(),
                section::make('Images :')->schema([
                    ImageEntry::make('images_url')
                        ->disk('public')
                        ->label('Images :')
                        ->size(150)
                        ->url(fn ($record, $state) => filled($state)
                            ? (str_starts_with($state, 'http')
                                ? $state
                                : Storage::disk('public')->url($state))
                            : null
                        )
                        ->openUrlInNewTab()
                        ->extraAttributes(['class' => 'cursor-zoom-in'])
                        ->columnSpanFull()               ])->columns(1)->columnSpanFull()
            ]);
    }
}
