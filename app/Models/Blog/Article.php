<?php

namespace App\Models\Blog;

use App\Models\Image;
use App\Models\Paragraph;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';
    protected $fillable = ['title','image','description'];
    public function paragraphs(){
        return $this->hasMany(Paragraph::class);
    }

}
