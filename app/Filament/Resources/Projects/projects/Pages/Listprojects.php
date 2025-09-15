<?php

namespace App\Filament\Resources\Projects\projects\Pages;

use App\Filament\Resources\Projects\projects\projectResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class Listprojects extends ListRecords
{
    protected static string $resource = projectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
