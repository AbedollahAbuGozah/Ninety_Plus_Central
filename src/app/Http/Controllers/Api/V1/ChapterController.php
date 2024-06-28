<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChapterRequest;
use App\Http\Resources\BaseResource;
use App\Http\Resources\ChapterResource;
use App\Models\Chapter;
use App\Models\Module;
use App\services\ChapterService;

class ChapterController extends BaseController
{
    public function __construct(protected ChapterService $chapterService)
    {

    }

    public function index(ChapterRequest $request, Module $module)
    {
        $chapters = Chapter::query()->with(['module']);
        return $this->success(ChapterResource::collection($chapters, $request->boolean('paginate'), $request->get('page_size')), trans('messages.success.index'), 200);
    }

    public function store(ChapterRequest $request, Module $module)
    {
        $validatedData = $request->safe()->all();
        $chapter = $this->chapterService->create($validatedData, new Chapter(), ['module']);
        return $this->success(ChapterResource::make($chapter), trans('messages.success.store'), 200);
    }

    public function show(ChapterRequest $request, Chapter $chapter)
    {
        $chapter = $this->chapterService->get($chapter, ['module']);
        return $this->success(ChapterResource::make($chapter), trans('messages.success.index'), 200);
    }

    public function update(ChapterRequest $request, Chapter $chapter)
    {
        $validatedData = $request->safe()->all();
        $chapter = $this->chapterService->update($validatedData, $chapter, []);
        return $this->success(ChapterResource::make($chapter), trans('messages.success.update'), 200);
    }

    public function destroy(ChapterRequest $request, Chapter $chapter)
    {
        $chapter->delete();
        return $this->success([], trans('messages.success.delete'), 200);
    }

}
