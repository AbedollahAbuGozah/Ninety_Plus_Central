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
        $this->resource = array_search(self::class, Gate::policies());
        parent::__construct();
    }

    public function viewAny(User $user): bool
    {
        return $user->authorize('view_access', $this->resource);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Course $course): bool
    {
        return $user->authorize('view_access', $this->resource);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->authorize('add_access', $this->resource);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Course $course): bool
    {
        if ($user->id != $course->instructor_id) {
            return false;
        }

        return $user->authorize('modify_access', $this->resource);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Course $course): bool
    {
        if ($user->id != $course->instructor_id || $course->students()->count()) {
            return false;
        }

        return $user->authorize('delete_access', $this->resource);
    }

    public function changeStatus(User $user, Course $course, $newStatus)
    {
        if ($newStatus == CourseStatusOptions::DRAFT && $course->status == CourseStatusOptions::ACTIVE && $course->students()->count()) {
            return false;
        }
        return $user->authorize('modify_access', $this->resource);


    }
}
