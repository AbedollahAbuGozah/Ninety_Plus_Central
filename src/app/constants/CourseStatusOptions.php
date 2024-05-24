<?php

namespace App\constants;

class CourseStatusOptions
{
    const DRAFT = 'draft';
    const ACTIVE = 'active';
    const OVER = 'over';
    const CANCELED = 'canceled';

    public static function options()
    {
        $reflection = new \ReflectionClass(__CLASS__);
        return array_values($reflection->getConstants());
    }
}
