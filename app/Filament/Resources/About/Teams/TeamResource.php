<?php

namespace App\Filament\Resources\About\Teams;

use App\Filament\Resources\About\Teams\Pages\CreateTeam;
use App\Filament\Resources\About\Teams\Pages\EditTeam;
use App\Filament\Resources\About\Teams\Pages\ListTeams;
use App\Filament\Resources\About\Teams\Pages\ViewTeam;
use App\Filament\Resources\About\Teams\Schemas\TeamForm;
use App\Filament\Resources\About\Teams\Schemas\TeamInfolist;
use App\Filament\Resources\About\Teams\Tables\TeamsTable;
use App\Models\About\Team;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Team';

    public static function form(Schema $schema): Schema
    {
        return TeamForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TeamInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TeamsTable::configure($table);
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
            'index' => ListTeams::route('/'),
            'create' => CreateTeam::route('/create'),
            'view' => ViewTeam::route('/{record}'),
            'edit' => EditTeam::route('/{record}/edit'),
        ];
    }
}
