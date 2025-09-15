<?php

namespace App\Filament\Resources\About\Services\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class ServiceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('details')->schema([
                    TextEntry::make('name')->label('Name :'),
                    TextEntry::make('description')->label('Description :'),
                ])->columns(2)->columnSpanFull(),

                Section::make('image')->schema([
                    ImageEntry::make('image')->label('Image :')->disk('public')
                        ->url(fn($record, $state) => filled($state)
                            ? (str_starts_with($state, 'http')
                                ? $state
                                : Storage::disk('public')->url($state))
                            : null
                        )
                        ->openUrlInNewTab()
                        ->extraAttributes(['class' => 'cursor-zoom-in']),

                ])->columnSpanFull(),


            ]);
    }
}
