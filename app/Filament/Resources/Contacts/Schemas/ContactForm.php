<?php

namespace App\Filament\Resources\Contacts\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ContactForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                textInput::make('first_name')->required(),
                textInput::make('last_name')->required(),
                textInput::make('email'),
                textInput::make('phone'),
                textInput::make('message')->required(),
//                textInput::make('read_at'),
                Checkbox::make('is_read')->label('Replied'),
            ])->columns([3]);
    }
}
