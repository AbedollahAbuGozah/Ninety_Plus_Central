<?php

namespace App\Models;

use App\constants\RoleOptions;

class Student extends User
{
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_students')->withTimestamps();
    }

    public function profile()
    {

        return $this->load(['courses.instructor', 'courses.module', 'branch', 'courses.chapters']);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function getIsJoinedAttribute(Course $course)
    {
        return $this->courses()->where('id', $course->id)->exists();
    }

    public function invoices()
    {
        return parent::invoices(); // TODO: Change the autogenerated stub
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('student', function ($query) {
            $query->whereHas('roles', function ($query) {
                return $query->where('name', RoleOptions::STUDENT);
            }
            );
        });
    }

}
