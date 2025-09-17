<?php

namespace App\Filament\Resources\Ceos\Pages;

use App\Filament\Resources\Ceos\CeoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCeo extends ViewRecord
{
    protected static string $resource = CeoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
