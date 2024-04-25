<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->name,
            'instructor' => $this->whenLoaded('instructor', $this->instructor, $this->instructor_id),
            'module_id' => $this->whenLoaded('module', $this->module, $this->module_id),

        ];
    }
}
