<?php

namespace App\Models;

use App\Models\Blog\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paragraph extends Model
{
    protected $table = 'paragraphs';
    protected $fillable = ['title','body','article_id','order'];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

}
