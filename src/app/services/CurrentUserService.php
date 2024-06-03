<?php

namespace App\services;

class CurrentUserService
{
    public static function logout()
    {
        auth()->logout();
    }

    public static function get()
    {
        return auth()->user();
    }
}


