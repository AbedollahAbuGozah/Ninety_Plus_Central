<?php

namespace App\services;


use App\Models\AnswerOption;
use Illuminate\Database\Eloquent\Model;

class QuestionService extends BaseService
{

    public function postCreateOrUpdate($data, Model $question): void
    {
        $this->assignOrUpdateAnswerOptionsToQuestions($data['answer_options'], $question);
    }

    private function assignOrUpdateAnswerOptionsToQuestions($answer_options, $question): void
    {
        $question->answerOptions()->delete();

        array_map(function ($options) use ($question) {
                $options['question_id'] = $question->id;
                (new AnswerOptionsService())->create($options, new AnswerOption());
        }, $answer_options);
    }

}


