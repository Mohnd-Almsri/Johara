<?php

namespace App\Filament\Resources\Projects\projects\Pages;

use App\Filament\Resources\Projects\projects\projectResource;
use Filament\Resources\Pages\CreateRecord;

class Createproject extends CreateRecord
{
    protected static string $resource = projectResource::class;
    protected function afterCreate(): void
    {
        /** @var \App\Models\Projects\Project $record */
        $record = $this->record;
        $state  = $this->form->getState();

        // Interior
        foreach (($state['interior_images'] ?? []) as $path) {
            $record->images()->create([
                'path' => $path,
                'type' => 'interior',
            ]);
        }

        // Exterior
        foreach (($state['exterior_images'] ?? []) as $path) {
            $record->images()->create([
                'path' => $path,
                'type' => 'exterior',
            ]);
        }
    }
}
