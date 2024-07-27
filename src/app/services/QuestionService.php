<?php

namespace App\services;


use App\Models\AnswerOption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class QuestionService extends BaseService
{

    public function postCreateOrUpdate($data, Model $question): void
    {
        $this->assignOrUpdateAnswerOptionsToQuestions($data['answer_options'], $question->id);
    }

    private function assignOrUpdateAnswerOptionsToQuestions($answer_options, $questionId): void
    {
        array_map(function ($options) use ($questionId) {
            $options['question_id'] = $questionId;
            if (isset($options['id'])) {
                $answerOption = AnswerOption::hydrate([Arr::only($options, 'id')])->first();
                 (new AnswerOptionsService())->update(Arr::except($options, 'id'), $answerOption);
            } else {
                (new AnswerOptionsService())->create($options, new AnswerOption());
            }
        }, $answer_options);
    }

}


