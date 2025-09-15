<?php

namespace App\Models\About;

use App\Models\Projects\Category;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Team extends Model
{
    protected $table = 'teams';
    protected $fillable = ['name','role','image'];
    protected static function booted(): void
    {

        static::updating(function (Team $team) {
            // بس لو حقل الصورة اتغير
            if ($team->isDirty('image')) {
                $oldPath = $team->getRawOriginal('image');

                if ($oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
        });
        static::deleting(function (Team $team) {
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

    protected $hidden = ['created_at', 'updated_at'];

}
