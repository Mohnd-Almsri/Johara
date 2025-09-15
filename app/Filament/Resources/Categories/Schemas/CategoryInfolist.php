<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class CategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
            section::make('details')->schema([
                TextEntry::make('name')
                    ->label('Name :')
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
                    ->extraAttributes(['class' => 'cursor-zoom-in'])// يخلي الصورة دائرية، احذفها لو بدك الصورة
                ,
            ])->columns(2)->columnSpanFull()
            ]);
    }
}
