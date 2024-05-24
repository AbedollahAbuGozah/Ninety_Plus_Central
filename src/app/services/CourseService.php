<?php

namespace App\services;


use App\Models\Course;

class CourseService extends BaseService
{
    public function changeStatus(Course $course, $data)
    {
        $course->update([
            'status' => $data['status'],
        ]);
    }

}


