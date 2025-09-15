<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
protected $fillable = ['path','imageable_id','imageable_type','type'];
    protected $hidden = ['created_at', 'updated_at'];

    protected static function booted(): void
    {
        static::deleting(function (Image $image) {
            if ($image->path) {
                // لو عندك disk مختلف، بدّله
                Storage::disk('public')->delete($image->path);
            }
        });
    }
    public function imageable()
    {
        return $this->morphTo();
    }
}
