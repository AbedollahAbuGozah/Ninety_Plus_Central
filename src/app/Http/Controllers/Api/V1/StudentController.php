<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\UserResource;
use App\Models\Course;
use App\Models\Student;
use App\Notifications\BingStudentNotification;
use App\Traits\HttpResponse;

class StudentController extends BaseController
{
    use HttpResponse;

    public function index(Course $course)
    {
        $students = $course->students;
        return $this->success(UserResource::collection($students), trans('messages.success.index'), 200);
    }

    public function show(Student $student)
    {
        return $this->success(UserResource::make($student), trans('messages.success.index'), 200);
    }

    public function bing(Student $student)
    {
        $student->notify(new BingStudentNotification());
    }
}
