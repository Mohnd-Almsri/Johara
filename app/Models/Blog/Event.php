<?php

namespace App\Models\Blog;

use App\Models\Projects\Category;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    //
    protected $table = 'events';
    protected $fillable = ['location','description','date','image'];
    protected static function booted(): void
    {

        static::updating(function (Event $event) {
            // بس لو حقل الصورة اتغير
            if ($event->isDirty('image')) {
                $oldPath = $event->getRawOriginal('image');

                if ($oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
        });

        static::deleting(function (Event $event) {
            // خُد المسار الخام من الداتابيز (مو الـ URL)
            DB::transaction(function () use ($event) {

                $originalPath = $event->getRawOriginal('image');
                if ($originalPath) {
                    Storage::disk('public')->delete($originalPath);
                }

            });

        });

    }
     public function imageUrl(): Attribute
     {
         return Attribute::make(
             get: fn () => ! empty($this->attributes['image'])
                 ? Storage::disk('public')->url($this->attributes['image'])
                 : null,
         );
     }
}
