<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\ChangeCourseStatusRequest;
use App\Http\Requests\CourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Models\Module;
use App\services\CourseService;
use AWS\CRT\HTTP\Request;

class CourseController extends BaseController
{


    public function __construct(protected CourseService $courseService)
    {

    }

    public function indexAll(CourseRequest $request)
    {
        $courses = Course::query()->with(['instructor', 'rates', 'chapters'])->filter()->sort();
        return $this->success(CourseResource::collection($courses, $request->boolean('paginate'), $request->get('page_size')), trans('messages.success.index'), 200);
    }

    public function index(CourseRequest $request, Module $module)
    {
        $courses = $module->courses()
            ->with(['instructor', 'module', 'chapters', 'rates'])
            ->filter()
            ->sort()
            ->withCount('students');

        return $this->success(CourseResource::collection($courses, $request->boolean('paginate'), $request->get('page_size')), trans('messages.success.index'), 200);
    }

    public function store(CourseRequest $request, Module $module)
    {
        $validatedData = $request->safe()->all();
        $course = $this->courseService->create($validatedData, new Course(), ['instructor', 'module', 'chapters']);
        return $this->success(CourseResource::make($course), trans('messages.success.store'), 200);
    }

    public function show(CourseRequest $request, Course $course)
    {
        $course = $this->courseService->get($course, ['instructor', 'module', 'chapters', 'rates']);
        return $this->success(CourseResource::make($course), trans('messages.success.index'), 200);
    }

    public function update(CourseRequest $request, Course $course)
    {
        $validatedData = $request->safe()->all();
        $course = $this->courseService->update($validatedData, $course, ['instructor', 'module', 'chapters']);
        return $this->success(CourseResource::make($course), trans('messages.success.update'), 200);
    }

    public function destroy(CourseRequest $request, Course $course)
    {
        $course->delete();
        return $this->success([], trans('messages.success.delete'), 200);
    }

    public function changeStatus(ChangeCourseStatusRequest $request, Course $course)
    {
        /*
         * active course can not canceled if it already has joined student
         */
        $validatedData = $request->safe()->all();
        $this->courseService->changeStatus($course, $validatedData);
        return $this->success($course, 'messages.success.updated', 200);
    }
}
