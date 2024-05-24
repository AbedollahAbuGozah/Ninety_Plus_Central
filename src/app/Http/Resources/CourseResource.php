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
            'title' => $this->title,
            'instructor' => $this->whenLoaded('instructor', function () {
                return [
                    'id' => $this->instructor_id,
                    'name' => $this->instructor->first_name
                ];
            }, $this->instructor_id),

            'module' => $this->whenLoaded('module', function () {
                return [
                    'id' => $this->module_id,
                    'name' => $this->module->name
                ];
            }, $this->module_id),
            'status' => $this->status
        ];
    }
}
