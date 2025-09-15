<?php

namespace App\Services;

use App\Models\About\About;
use App\Models\Blog\Ceo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CeoService
{
public function create($request){
$ceo = DB::transaction(function () use ($request) {
    $ceo = Ceo::create([
        'name' => $request->name,
        'paragraph_1' => $request->paragraph_1,
        'paragraph_2' => $request->paragraph_2,
        'paragraph_3' => $request->paragraph_3,
    ]);
    if ($request->hasFile('images')) {
        foreach ($request->images as $index => $image) {
            $ceo->ImageNumber = $index + 1;
            $path = $this->storeImage($image, $ceo);
            $ceo->images()->create([
                'path' => $path
            ]);
        }
    }
    unset($ceo->ImageNumber);
return $ceo;
}) ;
    return $ceo->load('images');
}

public function update($request){
    $ceo = DB::transaction(function () use ($request) {
        $ceo = ceo::where('id',$request->id)->first();

        $ceo->update([
            'name' => $request->name ?? $ceo->name,
            'paragraph_1' => $request->paragraph_1 ?? $ceo->paragraph_1,
            'paragraph_2' => $request->paragraph_2 ?? $ceo->paragraph_2,
            'paragraph_3' => $request->paragraph_3 ?? $ceo->paragraph_3,
        ]);

        if ($request->has('images')) {
            foreach ($request->images as $newImage) {
                if (!empty($newImage['image']) && $newImage['image']->isValid()) {
                    $oldImage = $ceo->images()->where('id', $newImage['id'])->first();

                    if (!$oldImage) {
                        throw new \Exception("هذه الصورة لا تعود لهذا المحتوى.");
                    }

                    if ($oldImage) {
                        Storage::disk('public')->delete($oldImage->path);

                        $ceo->ImageNumber = $oldImage->id;

                        $path = $this->storeImage($newImage['image'], $ceo);

                        $oldImage->update(['path' => $path]);
                    }
                }
            }
        }

        unset($ceo->ImageNumber);

        return $ceo->load('images');
    });
    return $ceo;

}
    public function delete($request)
    {
        $result = DB::transaction(function () use ($request) {
            $ceo = ceo::where('id', $request->id)->firstOrFail();

            $ceo->images->each(function ($image) {
                $image->delete();
            });

            // we will back soon
            $folderDeleted = Storage::disk('public')->deleteDirectory("blog/ceos/".$ceo->id);

            $ceo->delete();

            return $folderDeleted;
        });

        if ($result) {
            return true;
        } else {
            throw new \Exception("Failed to delete directory for ceo id {$request->id}");
        }
    }

    public function addImage($request)
    {
        $ceo = DB::transaction(function () use ($request) {
            $ceo = ceo::withCount('images')->findOrFail($request->id);

            if ($ceo->images_count >= 3) {
                throw new \Exception('You can not add more than 3 images.');
            }

            $ceo->ImageNumber=$ceo->images_count+1;
            $ceo->images()->create([
                'path' => $this->storeImage($request->image, $ceo),
            ]);
            return $ceo; });
        return $ceo;
    }

    private function storeImage($image, $ceo)
    {
        $extension = $image->getClientOriginalExtension();
        $filename = $ceo->ImageNumber . '.' . $extension;
        $folder = "blog/ceos" . '/' . $ceo->id;
        $path = $image->storeAs($folder, $filename, 'public');
        return $path;

    }
}
