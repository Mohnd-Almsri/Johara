<?php

namespace App\Models;

use App\Models\Blog\Article;
use App\Models\Projects\Category;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Paragraph extends Model
{
    protected $table = 'paragraphs';
    protected $fillable = ['title','body','article_id','order','image'];
    protected static function booted(): void
    {
        static::updating(function (Paragraph $category) {
            // بس لو حقل الصورة اتغير
            if ($category->isDirty('image')) {
                $oldPath = $category->getRawOriginal('image');

                if ($oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
        });

        static::deleting(function (Paragraph $paragraph) {
            // خُد المسار الخام من الداتابيز (مو الـ URL)
            DB::transaction(function () use ($paragraph) {

                $originalPath = $paragraph->getRawOriginal('image');
                if ($originalPath) {
                    Storage::disk('public')->delete($originalPath);
                }

            });
    });

    }


    protected $casts = ['order' => 'int'];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => ! empty($this->attributes['image'])
                ? Storage::disk('public')->url($this->attributes['image'])
                : null,
        );
    }
}
