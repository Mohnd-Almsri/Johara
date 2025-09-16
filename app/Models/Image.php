<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    protected $fillable = ['path', 'imageable_id', 'imageable_type', 'type'];
    protected $hidden = ['created_at', 'updated_at'];

    // (اختياري) خلّي الـ url يطلع تلقائيًا مع الـ JSON
    protected $appends = ['image_url'];

    protected static function booted(): void
    {
        static::deleting(function (Image $image) {
            if ($image->path) {
                Storage::disk('public')->delete($image->path);
            }
        });
    }

    public function imageable()
    {
        return $this->morphTo();
    }

    // Accessor حديث: image_url
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => ! empty($this->attributes['path'])
                ? Storage::disk('public')->url($this->attributes['path'])
                : null,
        );
    }
}
