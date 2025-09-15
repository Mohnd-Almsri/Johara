<?php

namespace App\Filament\Resources\Projects\projects\Pages;

use App\Filament\Resources\Projects\projects\projectResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class Viewproject extends ViewRecord
{
    protected static string $resource = projectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
