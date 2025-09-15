<?php

namespace App\Filament\Resources\Projects\projects\Pages;

use App\Filament\Resources\Projects\projects\projectResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class Editproject extends EditRecord
{
    protected static string $resource = projectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];

    }
    protected function afterSave(): void
    {
        /** @var \App\Models\Projects\Project $record */
        $record = $this->record;
        $state  = $this->form->getState();

        $newInterior = array_values($state['interior_images'] ?? []);
        $newExterior = array_values($state['exterior_images'] ?? []);

        // ===== Interior =====
        $current = $record->images()->where('type', 'interior')->pluck('path')->all();

        // حذف اللي انشال من الحقل
        $toDelete = array_diff($current, $newInterior);
        if (!empty($toDelete)) {
            $record->images()
                ->where('type', 'interior')
                ->whereIn('path', $toDelete)
                ->get()
                ->each->delete(); // يشغّل حذف الملف كمان
        }

        // إضافة الجديد
        foreach (array_diff($newInterior, $current) as $path) {
            $record->images()->create(['path' => $path, 'type' => 'interior']);
        }

        // ===== Exterior =====
        $current = $record->images()->where('type', 'exterior')->pluck('path')->all();

        $toDelete = array_diff($current, $newExterior);
        if (!empty($toDelete)) {
            $record->images()
                ->where('type', 'exterior')
                ->whereIn('path', $toDelete)
                ->get()
                ->each->delete();
        }

        foreach (array_diff($newExterior, $current) as $path) {
            $record->images()->create(['path' => $path, 'type' => 'exterior']);
        }
    }
}
