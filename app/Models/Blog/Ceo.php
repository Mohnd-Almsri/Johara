<?php

namespace App\Models\Blog;

use App\Models\Image;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Ceo extends Model
{
    protected $table = 'ceos';
    protected $fillable = ['name','paragraph_1','paragraph_2','paragraph_3'];


    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
    public function imagesUrl() :Attribute
{
    return Attribute::make(
        get: fn () => $this->images
            ? $this->images->pluck('path')->values()->all()
            : [],

    );
}
}
