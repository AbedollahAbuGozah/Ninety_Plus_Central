<?php

namespace App\constants;

class RatableTypeOptions
{
    const INSTRUCTOR = 'App\\Models\\Instructor';
    const COURSE = 'App\\Models\\Course';

    public static function options()
    {
        $reflection = new \ReflectionClass(__CLASS__);
        return $reflection->getConstants();
    }


}
