<?php

namespace App\constants;

class CommentableTypeOptions
{
    const LECTURE = 'App\\Models\\Lecture';
    const COURSE = 'App\\Models\\Course';

    public static function options()
    {
        $reflection = new \ReflectionClass(__CLASS__);
        return $reflection->getConstants();
    }


}
