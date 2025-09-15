<?php

namespace App\Trait;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Str;

trait ImagePathTrait
{

    public function imagePath($file, $modelName, $object)
    {

        $extension = $file->getClientOriginalExtension();
        if ($modelName === "category" ) {
            $filename = $object->id . '.' . $extension;
            $folder = "categories".'/'.$object->id;
            $path = $file->storeAs($folder, $filename, 'public');
            return $path;
        }elseif ($modelName === "about_us" ) {
            $filename = $object->ImageNumber . '.' . $extension;
            $folder = "about/about_us".'/'.$object->id;
            $path = $file->storeAs($folder, $filename, 'public');
            return $path;

        }


    }


}
