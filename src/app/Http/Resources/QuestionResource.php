<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class QuestionResource extends BaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'type' => $this->type,
            'label' => $this->label,
            'expectation_time' => $this->expectation_time . 'minutes',
            'weight' => $this->weight,
            'hint' => $this->hint,
            'chapter' => $this->whenLoaded('chapter', $this->chapter->only('id', 'title'), $this->chapter_id),
            'answers_options' => $this->whenLoaded('answerOptions', fn() => AnswerResource::collection($this->answerOptions))
        ];
    }
}
