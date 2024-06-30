<?php

namespace App\services;


use App\Models\Instructor;
use App\Models\Lecture;
use App\Models\Student;
use App\Models\User;

class LectureService extends BaseService
{

    public function __construct(protected UserService $userService, protected StreamService $streamService)
    {
    }

    public function joinLiveLecture(Lecture $lecture, User $user)
    {
        return $this->streamService->generateToken($user->id);
    }

    public function startLiveLecture(Lecture $lecture, Instructor $user)
    {
        $lecture->status = 'live';
    }
}


