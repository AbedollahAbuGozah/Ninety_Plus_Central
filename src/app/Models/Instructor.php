<?php

namespace App\Models;

use App\constants\Roles;

class Instructor extends User
{
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('instructor', function ($query) {
            $query->whereHas('roles', function ($query) {
                return $query->where('name', Roles::INSTRUCTOR);
            }
            );
        });
    }
}
