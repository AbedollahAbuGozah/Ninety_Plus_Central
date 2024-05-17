<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\CourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Models\Module;
use App\services\CourseService;

class CourseController extends BaseController
{


    public function __construct(protected CourseService $courseService)
    {

    }

    public function index(CourseRequest $request, Module $module)
    {
        $courses = $module->courses()->with(['instructor', 'module'])->get();


        return $this->success(CourseResource::collection($courses), trans('messages.success.index'), 200);
    }

    public function store(CourseRequest $request, Module $module)
    {
        $validatedData = $request->safe()->all();
        logger($request->isStore());
        $course = $this->courseService->create($validatedData, new Course(), ['instructor', 'module']);
        return $this->success(CourseResource::make($course), trans('messages.success.store'), 200);
    }

    public function show(CourseRequest $request, Course $course)
    {
        $course = $this->courseService->get($course, ['instructor', 'module']);
        return $this->success(CourseResource::make($course), trans('messages.success.index'), 200);
    }

    public function update(CourseRequest $request, Course $course)
    {
        $validatedData = $request->safe()->all();
        $course = $this->courseService->update($validatedData, $course, ['instructor', 'module']);
        return $this->success(CourseResource::make($course), trans('messages.success.update'), 200);
    }

    public function destroy(CourseRequest $request, Course $course)
    {
        $course->delete();
        return $this->success([], trans('messages.success.delete'), 200);
    }
}
