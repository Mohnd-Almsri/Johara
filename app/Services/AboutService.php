<?php

namespace App\Services;


use App\Models\About\About;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AboutService
{
    public function updateAbout($request)
    {
        $about = DB::transaction(function () use ($request) {
            $about = About::findOrFail($request->id);

            $about->update([
                'title' => $request->title ?? $about->title,
                'body' => $request->body ?? $about->body,
            ]);

            if ($request->has('images')) {
                foreach ($request->images as $newImage) {
                    if (!empty($newImage['image']) && $newImage['image']->isValid()) {
                        $oldImage = $about->images()->where('id', $newImage['id'])->first();

                        if (!$oldImage) {
                            throw new \Exception("هذه الصورة لا تعود لهذا المحتوى.",404);
                        }

                        if ($oldImage) {
                            Storage::disk('public')->delete($oldImage->path);

                            // استخدم هذا للتمييز بالصورة
                            $about->ImageNumber = $oldImage->id;

                            $path = $this->storeImage($newImage['image'], $about);

                            $oldImage->update(['path' => $path]);
                        }
                    }
                }
            }

            if ($request->has('deleted_images')) {
                foreach ($request['deleted_images'] as $id) {
                    $image = $about->images->firstWhere('id', $id); // استخدام الكولكشن يلي محمل مسبقاً لتجنب استعلام زائد
                    if ($image) {
                        Storage::disk('public')->delete($image->path);
                        $image->delete();
                    }
                }
            }

            unset($about->ImageNumber);

            return $about->load('images');
        });
        return $about;
    }

    public function createAbout($request)
    {
        $about = DB::transaction(function () use ($request) {
            $about = About::create([
                'title' => $request->title,
                'body' => $request->body
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->images as $index => $image) {
                    $about->ImageNumber = $index + 1;
                    $path = $this->storeImage($image, $about);
                    $about->images()->create([
                        'path' => $path
                    ]);
                }
            }
            unset($about->ImageNumber);
            return $about->load('images');
        });
        return $about;

    }

    public function deleteAbout($request)
    {
        $result = DB::transaction(function () use ($request) {
            $about = About::where('id', $request->id)->firstOrFail();

            $about->images->each(function ($image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            });

            // we will back soon
            $folderDeleted = Storage::disk('public')->deleteDirectory("about/about_us/{$about->id}");

            $about->delete();

            return $folderDeleted;
        });

        if ($result) {
            return true;
        } else {
            throw new \Exception("Failed to delete directory for about id {$request->id}");
        }
    }

    public function addImage($request)
    {
        $about = DB::transaction(function () use ($request) {
            $about = About::withCount('images')->findOrFail($request->id);

            if ($about->images_count >= 2) {
                throw new \Exception('You can not add more than 2 images.',422);
            }

            $about->ImageNumber=$about->images_count+1;
            $about->images()->create([
                'path' => $this->storeImage($request->image, $about),
            ]);
       return $about; });
        return $about;
    }

    public function storeImage($image, $about)
    {
        $extension = $image->getClientOriginalExtension();
        $filename = $about->ImageNumber . '.' . $extension;
        $folder = "about/about_us" . '/' . $about->id;
        $path = $image->storeAs($folder, $filename, 'public');
        return $path;

    }


}
