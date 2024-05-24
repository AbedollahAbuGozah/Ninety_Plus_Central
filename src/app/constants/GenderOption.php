<?php

namespace App\constants;

class GenderOption
{
    const MALE = 'male';
    const FEMALE = 'female';
    public static function options()
    {
        $reflection = new \ReflectionClass(__CLASS__);
        return array_values($reflection->getConstants());
    }

}
