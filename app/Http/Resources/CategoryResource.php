<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * متغير للتحكم بتحميل العلاقات
     */
    protected bool $withRelations = false;

    /**
     * تفعيل إرجاع العلاقات
     */
    public function withRelations(bool $value = true): self
    {
        $this->withRelations = $value;
        return $this;
    }

    /**
     * تحويل الريسورس إلى Array
     */
    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'image' => $this->image_url,

            // ما ترجع المشاريع إلا إذا الخاصية مفعلة
            'projects' => $this->when(
                $this->withRelations,
                fn () => ProjectResource::collection($this->whenLoaded('projects'))
            ),
        ];
    }
}
