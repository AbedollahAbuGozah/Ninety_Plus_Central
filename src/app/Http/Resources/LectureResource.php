<?php

namespace App\Http\Resources;

use App\Models\Lecture;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class LectureResource extends BaseResource
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
            'description' => $this->description,
            'course' => $this->whenLoaded('course', fn() => $this->course()->select(['id', 'title as name'])->get(), $this->course_id),
            'chapter' => $this->whenLoaded('chapter', fn() => $this->chapter()->select(['id', 'title as name'])->get(), $this->chapter_id),

            'record_link' => $this->when($this->status == 'over', fn() => Storage::disk('s3')->temporaryUrl(
                $this->getFirstMediaPath(Lecture::LECTURE_RECORD_COLLECTION),
                now()->addMinutes(60*3)
            )),

            'starts_at' => $this->starts_at,
            'status' => $this->status,
        ];
    }
}
