<?php

namespace App\Filament\Resources\Ceos\Pages;

use App\Filament\Resources\Ceos\CeoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCeo extends CreateRecord
{
    protected static string $resource = CeoResource::class;
    protected function afterCreate(): void
    {
        /** @var \App\Models\Blog\Ceo $record */
        $record = $this->record;
        $state  = $this->form->getState();
        foreach (($state['images'] ?? []) as $path) {
            $record->images()->create([
                'path' => $path,
            ]);
        }

    }

}
