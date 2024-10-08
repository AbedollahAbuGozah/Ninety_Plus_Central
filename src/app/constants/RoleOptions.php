<?php

namespace App\constants;

class RoleOptions
{
    const STUDENT = 'student';
    const ADMIN = 'admin';
    const INSTRUCTOR = 'instructor';
    const HR = 'hr';

    public static function options()
    {
        $reflection = new \ReflectionClass(__CLASS__);
        return array_values($reflection->getConstants());
    }

}
