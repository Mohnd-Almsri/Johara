<?php

namespace App\Services;

use App\Models\About\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ServiceService
{
    public function serviceCreate($request)
    {

        $service = DB::transaction(function () use ($request) {

            $service = Service::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);
            $service->update([
                'image' => $this->storeImage($request->image, $service)
            ]);
            return $service;
        });

        return $service;
    }

    public function serviceUpdate($request)
    {
        $service = DB::transaction(function () use ($request) {
            $service = Service::findOrFail($request->id);


            $service->update([
                'name' => $request->name ?? $service->name,
                'description' => $request->description ?? $service->description,
            ]);

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                Storage::disk('public')->delete($service->image);

                $newImagePath = $this->storeImage($request->file('image'), $service);
                $service->update(['image' => $newImagePath]);
            }

            return $service;
        });

        return $service;
    }

    public function serviceDelete($request){
        $service = DB::transaction(function () use ($request) {
            $service = service::find($request->id);
            Storage::disk('public')->deleteDirectory('services/'.$service->id);
            $service->delete();
        });
        return true;
    }

    private function storeImage($image, $service)
    {
        $extension = $image->getClientOriginalExtension();

        $filename = str_replace(' ', '_', $service->name) . '.' . $extension;
        $folder = "services" . '/' . $service->id;
        $path = $image->storeAs($folder, $filename, 'public');
        return $path;

    }
}
