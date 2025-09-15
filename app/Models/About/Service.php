<?php

namespace App\Models\About;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Service extends Model
{
    Protected $fillable=['name','description','image'];
    protected $hidden = ['created_at', 'updated_at'];

    protected static function booted(): void
    {
        static::updating(function (Service $service) {
            // بس لو حقل الصورة اتغير
            if ($service->isDirty('image')) {
                $oldPath = $service->getRawOriginal('image');

                if ($oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
        });

        static::deleting(function (Service $team) {
            // خُد المسار الخام من الداتابيز (مو الـ URL)
            DB::transaction(function () use ($team) {

                $originalPath = $team->getRawOriginal('image');
                if ($originalPath) {
                    Storage::disk('public')->delete($originalPath);
                }
            });

        });

    }
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value
                ? Storage::disk('public')->url($value)  // يتحوّل لـ http://.../storage/...
                : null,
        );
    }

}
