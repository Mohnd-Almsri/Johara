<?php

namespace App\Models\Projects;

use App\Models\Image;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    protected $table = 'projects';
    protected $fillable = [
        'name',
        'main_description',
        'second_description',
        'third_description',
        'location',
        'date',
        'contractor',
        'category_id',
        'details',
        'mainImage',
    ];
    protected $casts = [
        'details' => 'array',
    ];

    protected static function booted(): void
    {
        static::updating(function (Project $project) {
            // بس لو حقل الصورة اتغير
            if ($project->isDirty('mainImage')) {
                $oldPath = $project->getRawOriginal('mainImage');

                if ($oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
        });
        static::deleting(function ($project) {
            // احذف سجلات الصور وحدة وحدة ليشتغل هوك Image::deleting
            DB::transaction(function () use ($project) {

                $originalPath = $project->getRawOriginal('mainImage');
                if ($originalPath) {
                    Storage::disk('public')->delete($originalPath);
                }
                $project->images()->get()->each->delete();

            });
        });
    }



    protected function mainImageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => ! empty($this->attributes['mainImage'])
                ? Storage::disk('public')->url($this->attributes['mainImage'])
                : null,
        );
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
    protected function interiorImages(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->images
                ? $this->images->where('type', 'interior')->pluck('path')->values()->all()
                : [],
            set: function ($paths) {
                // paths ممكن تكون null أول تحميل
                $paths = array_values(array_filter((array) $paths));

                // السينك حسب النوع
                $this->syncImagesByType('interior', $paths);
                // ما منخزن بقيمة عمود بالمشروع، عم نكتب بالعلاقة
                return null;
            }
        );
    }

    /**
     * exterior_images: Array من المسارات (strings)
     */
    protected function exteriorImages(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->images
                ? $this->images->where('type', 'exterior')->pluck('path')->values()->all()
                : [],
            set: function ($paths) {
                $paths = array_values(array_filter((array) $paths));
                $this->syncImagesByType('exterior', $paths);
                return null;
            }
        );
    }

    /**
     * مزامنة الصور حسب النوع: تضيف الجديد وتشيل اللي ما عاد موجود
     */
    protected function syncImagesByType(string $type, array $newPaths): void
    {
        // انتبه: لو الموديل مو محفوظ لسا، أجّل للمرة اللاحقة
        if (! $this->exists) {
            // نخزّن مؤقتاً لحد يصير save() وبعدين منقدر نعمل hook إذا بدك
            // بس عملياً Filament بيحفظ record قبل ما يمرّر set attributes، فتمام.
            return;
        }

        $current = $this->images()->where('type', $type)->pluck('path')->all();

        $toDelete = array_diff($current, $newPaths);
        $toAdd    = array_diff($newPaths, $current);

        if (! empty($toDelete)) {
            $this->images()
                ->where('type', $type)
                ->whereIn('path', $toDelete)
                ->delete();
        }

        foreach ($toAdd as $path) {
            $this->images()->create([
                'path' => $path,
                'type' => $type,
            ]);
        }
    }

}
