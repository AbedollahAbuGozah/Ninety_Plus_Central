<?php

namespace App\Models;

use App\constants\Roles;

class Student extends User
{
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_students');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('student', function ($query) {
            $query->whereHas('roles', function ($query) {
                return $query->where('name', Roles::STUDENT);
            }
            );
        });
    }

}
