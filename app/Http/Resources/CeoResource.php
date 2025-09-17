<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CeoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'paragraph_1' => $this->paragraph_1,
            'paragraph_2' => $this->paragraph_2,
            'paragraph_3' => $this->paragraph_3,
            'images' => $this->whenLoaded('images', function () {
                return $this->images->map(function ($image) {
                    return [
                        'url' => $image->image_url,
                    ];

                });
            })
        ];

    }
}
