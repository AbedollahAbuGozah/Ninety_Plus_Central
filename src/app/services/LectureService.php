<?php

namespace App\services;


use App\Models\Instructor;
use App\Models\Lecture;
use App\Models\Student;

class LectureService extends BaseService
{

    public function __construct(protected UserService $userService)
    {
    }

    public function joinStudent(Lecture $lecture, Student $student)
    {
        return $this->userService->generateLectureToken($lecture, $student);
    }

    public function startLiveLecture(Lecture $lecture, Instructor $user)
    {
    }
}


