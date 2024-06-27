<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class ChapterResource extends BaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'lectures' => $this->lectures(),
        ];
    }
}
