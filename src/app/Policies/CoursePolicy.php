<?php

namespace App\Policies;

use App\constants\CourseStatusOptions;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class CoursePolicy extends BasePolicy
{
    public function __construct()
    {
       self::$resource = "Courses";
        parent::__construct();
    }

    public function viewAny(User $user): bool
    {
        return self::check($user, 'view_access');
    }

    public function view(User $user, Course $course): bool
    {
        return self::check($user, 'view_access');
    }


    public function create(User $user): bool
    {
        return self::check($user, 'add_access');
    }


    public function update(User $user, Course $course): bool
    {
        return self::check($user, 'modify_access');
    }

    public function delete(User $user, Course $course): bool
    {
        return self::check($user, 'delete_access');

    }

    public function changeStatus(User $user, Course $course, $newStatus)
    {

    }
}
