<?php

namespace App\Models\About;

use App\Models\Image;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    protected $table = 'abouts';
    protected $fillable = ['body', 'title'];
protected $hidden = ['created_at', 'updated_at'];
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}

