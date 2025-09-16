<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'description'=>$this->description,
            'date'=>$this->id,
            'contractor'=>$this->contractor,
            'category_name'=>$this->whenLoaded('category',fn()=>$this->category->name),
            'details'=>$this->details,
            'mainImage'=>$this->mainImage_url,
            'images' => $this->whenLoaded('images', function () {
                $map = fn ($img) => [
                    'url'  => $img->image_url, // accessor من الموديل
                ];

                return [
                    'exterior' => $this->images->where('type', 'exterior')->values()->map($map),
                    'interior' => $this->images->where('type', 'interior')->values()->map($map),
                ];
            }),
        ];
    }
}
