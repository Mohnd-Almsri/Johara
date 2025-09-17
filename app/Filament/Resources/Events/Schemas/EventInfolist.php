<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class EventInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                section::make('details')->schema([
                    TextEntry::make('location')
                        ->label('Location :')
                    ,TextEntry::make('date')
                        ->label('Date :')
                    ,TextEntry::make('description')
                        ->label('Description :')->columnSpanFull()
                    ,

                ])->columns(2)->columnSpanFull()
            ,
                  section::make('Image')->schema([
                      ImageEntry::make('image')
                          ->label('Image :')
                          ->size(200) // حجم الصورة بالـ px
                          ->disk('public')
                          ->url(fn($record, $state) => filled($state)
                              ? (str_starts_with($state, 'http')
                                  ? $state
                                  : Storage::disk('public')->url($state))
                              : null
                          )
                          ->openUrlInNewTab()
                          ->extraAttributes(['class' => 'cursor-zoom-in'])// يخلي الصورة دائرية، احذفها لو بدك الصورة
                      ,
                  ])->columns(2)->columnSpanFull()

                //
            ]);
    }
}
