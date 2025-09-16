<?php

namespace App\Models\Blog;

use App\Models\Image;
use App\Models\Paragraph;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    protected $table = 'articles';
    protected $fillable = ['title','image','description'];

    protected static function booted(): void
    {
        static::updating(function (Article $article) {
            if($article->isDirty('image')) {
                $oldPath = $article->getRawOriginal('image');
                if($oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

        });
        static::deleting(function (Article $a) {
            $a->paragraphs()->get()->each->delete();
            Storage::disk('public')->delete($a->image);
        });
    }

    public function paragraphs(){
        return $this->hasMany(Paragraph::class)->orderBy('order');
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
