<?php

namespace App\Http\Resources;

use App\Facades\NinetyPlusCentralFacade;
use Illuminate\Http\Request;
use Maize\Markable\Models\Favorite;

class CourseResource extends BaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'price' => $this->price,
            'instructor' => $this->whenLoaded('instructor', function () {
                return [
                    'id' => $this->instructor_id,
                    'name' => $this->instructor->first_name
                ];
            }, $this->instructor_id),
            'students' => $this->whenLoaded('student', fn() => $this->student->select('id', 'name')),
            'module' => $this->whenLoaded('module', function () {
                return [
                    'id' => $this->module_id,
                    'name' => $this->module->name
                ];
            }, $this->module_id),
            'description' => $this->description,

            'chapters' => $this->when(
                $this->relationLoaded('chapters') && $this->chapters->isNotEmpty(),
                fn() => ChapterResource::collection($this->chapters),
                '*'
            ),

            'cover_image' => $this->when(isset($this->properties['cover_image']), fn() => $this->properties['cover_image']),
            'intro_video' => $this->when(isset($this->properties['intro_video']), fn() => $this->properties['intro_video']),
            'weekly_lectures' => $this->when(isset($this->properties['weekly_lectures']), fn() => $this->properties['weekly_lectures']),
            'welcome_message' => $this->when(isset($this->properties['welcome_message']), fn() => $this->properties['welcome_message']),
            'ending_message' => $this->when(isset($this->properties['ending_message']), fn() => $this->properties['ending_message']),
            'rate' => $this->whenLoaded('rates', fn() => NinetyPlusCentralFacade::calcRatableRate($this)),
            'students_count' => $this->students_count,
            'is_favorite' => $this->when(auth()->user()->isStudent(), fn() => Favorite::has($this->resource, auth()->user())),
            'status' => $this->status
        ];
    }
}
