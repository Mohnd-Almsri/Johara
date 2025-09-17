<?php

namespace App\Filament\Resources\Ceos\Pages;

use App\Filament\Resources\Ceos\CeoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCeos extends ListRecords
{
    protected static string $resource = CeoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
