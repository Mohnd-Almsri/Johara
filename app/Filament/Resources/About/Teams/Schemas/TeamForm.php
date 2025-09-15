<?php

namespace App\Filament\Resources\About\Teams\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TeamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('role')
                    ->default(null),
                FileUpload::make('image')
                    ->label('Image')
                    ->image() //
                    ->disk('public')
                    ->directory('Team') // يخزن الصور داخل مجلد storage/app/public/categories
                    ->required(),
            ]);
    }
}
