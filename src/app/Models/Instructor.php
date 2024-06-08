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
