<?php

namespace App\Filament\Resources\Contacts\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;

class ContactInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                section::make('ContactInfo')->schema([
                    textEntry::make('first_name')->label('First Name :'),
                    textEntry::make('last_name')->label('Last Name :'),
                    textEntry::make('phone')->label('Phone :'),
                    textEntry::make('email')->label('Email :'),
                    textEntry::make('message')->label('Message')->columnSpanFull(),
                    TextEntry::make('is_read')
                        ->label('Replied')
                        ->formatStateUsing(fn($state) => $state ? 'yes' : 'not yet')
                        ->badge()
                        ->color(fn($state) => $state ? 'success' : 'warning')
                ])->columnSpanFull()->columns(2)
                //
            ]);
    }
}
