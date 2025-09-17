<?php

namespace App\Filament\Resources\Ceos;

use App\Filament\Resources\Ceos\Pages\CreateCeo;
use App\Filament\Resources\Ceos\Pages\EditCeo;
use App\Filament\Resources\Ceos\Pages\ListCeos;
use App\Filament\Resources\Ceos\Pages\ViewCeo;
use App\Filament\Resources\Ceos\Schemas\CeoForm;
use App\Filament\Resources\Ceos\Schemas\CeoInfolist;
use App\Filament\Resources\Ceos\Tables\CeosTable;
use App\Models\Blog\Ceo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CeoResource extends Resource
{
    protected static ?string $model = Ceo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserCircle;

    protected static ?string $recordTitleAttribute = 'Ceo';
    protected static ?string $navigationLabel = 'Ceo';
    protected static ?int $navigationSort =7;


    public static function form(Schema $schema): Schema
    {
        return CeoForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CeoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CeosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCeos::route('/'),
            'create' => CreateCeo::route('/create'),
            'view' => ViewCeo::route('/{record}'),
            'edit' => EditCeo::route('/{record}/edit'),
        ];
    }
}
