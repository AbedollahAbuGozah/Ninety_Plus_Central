<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\QuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Module;
use App\Models\Question;
use App\services\QuestionService;

class QuestionController extends BaseController
{
    public function __construct(private readonly QuestionService $questionService)
    {

    }

    public function index(QuestionRequest $request, Module $module)
    {
        $questions = Question::query()->with([])
            ->filter()
            ->sort();

        return $this->success(QuestionResource::collection($questions, $request->boolean('paginate'), $request->get('page_size')), trans('messages.success.index'), 200);
    }

    public function store(QuestionRequest $request, Module $module)
    {
        $validatedData = $request->safe()->all();
        $question = $this->questionService->create($validatedData, new Question(), []);
        return $this->success(QuestionResource::make($question), trans('messages.success.store'), 201);
    }

    public function show(QuestionRequest $request, Question $question)
    {
        $question = $this->questionService->get($question, ['answerOptions']);
        return $this->success(QuestionResource::make($question), trans('messages.success.index'), 200);
    }

    public function update(QuestionRequest $request, Question $question)
    {
        $validatedData = $request->safe()->all();
        $question = $this->questionService->update($validatedData, $question, []);
        return $this->success(QuestionResource::make($question), trans('messages.success.update'), 200);
    }

    public function destroy(QuestionRequest $request, Question $question)
    {
        $question->delete();
        return $this->success([], trans('messages.success.delete'), 200);
    }

}
