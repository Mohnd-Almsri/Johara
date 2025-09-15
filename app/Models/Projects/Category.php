<?php

namespace App\Models\Projects;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name', 'image'];

    protected static function booted(): void
    {

        static::updating(function (Category $category) {
            // بس لو حقل الصورة اتغير
            if ($category->isDirty('image')) {
                $oldPath = $category->getRawOriginal('image');

                if ($oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
        });

        static::deleting(function (Category $category) {
            // خُد المسار الخام من الداتابيز (مو الـ URL)
            DB::transaction(function () use ($category) {

                $originalPath = $category->getRawOriginal('image');
            if ($originalPath) {
                Storage::disk('public')->delete($originalPath);
            }
                $category->projects()->get()->each->delete();

            });

        });

    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    // Accessor: يرجّع URL كامل للواجهة / API
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value
                ? Storage::disk('public')->url($value)  // يتحوّل لـ http://.../storage/...
                : null,
        );
    }
}
