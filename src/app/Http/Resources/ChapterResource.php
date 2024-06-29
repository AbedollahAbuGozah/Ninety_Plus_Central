<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use function Laravel\Prompts\select;

class ChapterResource extends BaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'module' => $this->whenLoaded('module', fn() => $this->module()->select('id', 'name')->get(), $this->module_id),
            'lectures' => LectureResource::collection($this->lectures)
        ];
    }
}
