<?php

namespace App\Models;

use App\constants\RoleOptions;
use App\Traits\HasRates;

class Instructor extends User
{
    use HasRates;

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function profile()
    {
        return $this;
    }

    public function getTotalEarningsAttribute()
    {
        return $this->courses()->withCount('students')->get()->sum(function ($course) {
            return $course->students_count * $course->price;
        });
    }

    public function getTodayEarningsAttribute()
    {
        return $this->courses()
            ->withCount(['students' => function ($query) {
                $query
                    ->where('course_students.created_at', '>=', today()->startOfDay())
                    ->where('course_students.created_at', '<=', today()->endOfDay());
            }])
            ->get()
            ->sum(function ($course) {
                return $course->students_count * $course->price;
            });
    }

    public function getNumberOfSalesAttribute()
    {
        return CourseStudent::query()->whereHas('course', function ($query) {
            $query->where('instructor_id', $this->id);
        })->count();
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('instructor', function ($query) {
            $query->whereHas('roles', function ($query) {
                return $query->where('name', RoleOptions::INSTRUCTOR);
            }
            );
        });
    }
}
