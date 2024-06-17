<?php

namespace App\constants;

class FavorableTypeOptions
{
    const COURSE = 'App\\Models\\Course';

    public static function options()
    {
        $reflection = new \ReflectionClass(__CLASS__);
        return $reflection->getConstants();
    }


}
