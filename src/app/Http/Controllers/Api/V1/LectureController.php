<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\LectureRequest;
use App\Http\Resources\LectureResource;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Lecture;
use App\Models\Student;
use App\services\LectureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LectureController extends BaseController
{
    public function __construct(protected LectureService $lectureService)
    {

    }

    public function index(LectureRequest $request, Course $course = null)
    {
        $lectures = Lecture::query()->with(['course', 'chapter'])
            ->byRole(auth()->user(), $course)
            ->filter()
            ->sort();

        return $this->success(LectureResource::collection($lectures, $request->boolean('paginate'), $request->get('page_size')), trans('messages.success.index'), 200);
    }

    public function store(LectureRequest $request)
    {
        $validatedData = $request->safe()->all();
        $lecture = $this->lectureService->create($validatedData, new Lecture(), ['course', 'chapter']);
        return $this->success(LectureResource::make($lecture), trans('messages.success.store'), 201);
    }

    public function show(LectureRequest $request, Lecture $lecture)
    {
        $lecture = $this->lectureService->get($lecture, ['course', 'chapter']);
        return $this->success(LectureResource::make($lecture), 'messages.success.show', 200);
    }

    public function update(LectureRequest $request, Lecture $lecture)
    {
        $validatedData = $request->safe()->all();
        $this->lectureService->update($validatedData, $lecture, ['course', 'chapter']);
        return $this->success($lecture, 'messages.success.update', 200);
    }

    public function destroy(LectureRequest $request, Lecture $lecture)
    {
        $this->lectureService->delete($lecture);
        return $this->success([], 'messages.success.delete', 200);
    }

    public function joinLiveLecture(Request $request, Lecture $lecture)
    {
        $token = $this->lectureService->joinLiveLecture($lecture, auth()->user()->resolveUser());
        return $this->success([
            'token' => $token,
        ], trans('message.success.join_live'), 200);
    }

    public function startLiveLecture(Request $request, Lecture $lecture)
    {

        if ($lecture->status != 'draft') {
            return $this->error('messages.error.startLive', 400);
        }

        $lecture->update(
            [
                'status' => 'live',
            ]
        );


        return $this->success([], 'messages.success.startLive', 200);

    }

    public function uploadRecord(Request $request, Lecture $lecture)
    {
        $lectureRecord = $lecture->addMediaFromRequest('record')
            ->withCustomProperties(['visibility' => 'public'])
            ->toMediaCollection(Lecture::LECTURE_RECORD_COLLECTION);

        return $this->success([], 'uploaded', 204);
    }

}
