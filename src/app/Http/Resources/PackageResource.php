<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class PackageResource extends BaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'description' => $this->description,
            'module' => $this->whenLoaded('module', fn() => $this->module->only('id', 'name'), $this->id),
            'chapters' => $this->whenLoaded('chapters', fn() => $this->chapters->map(fn($chapter) => $chapter->only('id', 'title'))),
            'discount' => $this->when($this->discount, $this->discount),
            'cover_image' => $this->cover_image,
            'gifted_points' => $this->gifted_points,
            'discount_expires_at' => $this->when($this->discount, $this->discount_expires_at),
        ];
    }
}
