<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class AnswerResource extends BaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'question_id' => $this->question_id,
            'seq' => $this->when(!!$this->seq, $this->seq),
            'answer_id' => $this->when(!!$this->answer_id, $this->answer_id),
            'is_correct' => $this->when(!!$this->is_correct, $this->is_correct)
        ];
    }
}
