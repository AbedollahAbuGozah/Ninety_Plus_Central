<?php

namespace App\Http\Controllers\Api\V1;


use App\constants\MarkTypeOptions;
use App\Facades\NinetyPlusCentralFacade;
use Illuminate\Http\Request;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class MarkController extends BaseController
{

    public function index(Request $request, $markType, $markableType)
    {
        if ($markType == MarkTypeOptions::REACTION) {
            $request->validate(['reaction_type' => 'required|in:' . implode(',', config('markable.allowed_values.reaction'))]);
        }

        $allowedMarks = MarkTypeOptions::options();

        if (!in_array($markType, $allowedMarks)) {
            throw new RouteNotFoundException();
        }

        $markableClass = MarkTypeOptions::getMarkableClass($markType, $markableType);

        if (!$markableClass) {
            throw new RouteNotFoundException();
        }

        $modelResource = NinetyPlusCentralFacade::getModelResource($markableClass);

        $markableObjects = $markableClass::{'whereHas' . ucfirst($markType)}(auth()->user(), $request->get('reaction_type'));

        $markableObjects = $modelResource::collection($markableObjects, $request->boolean('paginate'), $request->get('page_size'));

        return $this->success($markableObjects, 'message.mark.success', 200);

    }

    public function mark(Request $request, $markType, $markableType, $markableId)
    {

        if ($markType == MarkTypeOptions::REACTION) {
            $request->validate(['reaction_type' => 'required|in:' . implode(',', config('markable.allowed_values.reaction'))]);
        }

        $allowedMarks = MarkTypeOptions::options();

        if (!in_array($markType, $allowedMarks)) {
            throw new RouteNotFoundException();
        }

        $markClass = MarkTypeOptions::getMarkClass($markType);
        $markableClass = MarkTypeOptions::getMarkableClass($markType, $markableType);

        if (!$markableClass) {
            throw new RouteNotFoundException();
        }

        $markableModel = $markableClass::findOrfail($markableId);

        $markClass::add($markableModel, auth()->user(), $request->get('reaction_type'));

        return $this->success([], 'message.mark.success', 200);
    }

    public function unMark(Request $request, $markType, $markableType, $markableId)
    {

        if ($markType == MarkTypeOptions::REACTION) {
            $request->validate(['reaction_type' => 'required|in:' . implode(',', config('markable.allowed_values.reaction'))]);
        }

        $allowedMarks = MarkTypeOptions::options();

        if (!in_array($markType, $allowedMarks)) {
            throw new RouteNotFoundException();
        }

        $markClass = MarkTypeOptions::getMarkClass($markType);
        $markableClass = MarkTypeOptions::getMarkableClass($markType, $markableType);

        if (!$markableClass) {
            throw new RouteNotFoundException();
        }

        $markableModel = $markableClass::findOrfail($markableId);

        $markClass::remove($markableModel, auth()->user(), $request->get('reaction_type'));

        return $this->success([], 'message.unmark.success', 200);
    }


}
