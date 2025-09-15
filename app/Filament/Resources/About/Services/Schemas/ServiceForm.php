<?php

namespace App\Filament\Resources\About\Services\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('description')
                    ->required(),
                FileUpload::make('image')
                    ->label('Image')
                    ->image() //
                    ->disk('public')
                    ->directory('Services') // يخزن الصور داخل مجلد storage/app/public/categories
                    ->required(),
            ]);
    }
}
