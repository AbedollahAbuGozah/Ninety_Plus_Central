<?php

namespace App\services;


use App\Models\Instructor;
use App\Models\Lecture;
use App\Models\Student;

class LectureService extends BaseService
{

    public function __construct(protected UserService $userService, protected StreamService $streamService)
    {
    }

    public function joinStudent(Lecture $lecture, Student $student)
    {
        return $this->streamService->generateToken($student->id);

    }

    public function startLiveLecture(Lecture $lecture, Instructor $user)
    {
        $lecture->status = 'active';
    }
}


