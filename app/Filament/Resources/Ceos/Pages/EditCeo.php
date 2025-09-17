<?php

namespace App\Filament\Resources\Ceos\Pages;

use App\Filament\Resources\Ceos\CeoResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCeo extends EditRecord
{
    protected static string $resource = CeoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
    protected function afterSave(): void
    {
        /** @var \App\Models\Blog\Ceo $record */
        $record = $this->record;
        $state  = $this->form->getState();
        // Interior
        $images = array_values($state['images'] ?? []);

        // ===== Interior =====
        $current = $record->images()->pluck('path')->all();

        // حذف اللي انشال من الحقل
        $toDelete = array_diff($current, $images);
        if (!empty($toDelete)) {
            $record->images()
                ->whereIn('path', $toDelete)
                ->get()
                ->each->delete(); // يشغّل حذف الملف كمان
        }

        // إضافة الجديد
        foreach (array_diff($images, $current) as $path) {
            $record->images()->create(['path' => $path]);
        }


    }

}
