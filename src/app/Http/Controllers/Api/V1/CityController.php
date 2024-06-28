<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChapterRequest;
use App\Http\Resources\BaseResource;
use App\Http\Resources\ChapterResource;
use App\Http\Resources\CityResource;
use App\Models\Chapter;
use App\Models\City;
use App\Models\Module;
use App\services\ChapterService;

class CityController extends BaseController
{
    public function __construct(protected ChapterService $chapterService)
    {

    }

    public function index(ChapterRequest $request, Module $module)
    {
        $cites = City::query();
        return $this->success(CityResource::collection($cites, $request->boolean('paginate'), $request->get('page_size')), trans('messages.success.index'), 200);
    }

}
