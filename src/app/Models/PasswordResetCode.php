<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PasswordResetCode extends Model
{
    use HasFactory;
    protected $table = 'password_reset_codes';

    public static function generateCode(){
        return Str::random(10);
    }

    public static function generateToken($email)
    {
        $token = Str::random(60);
        Cache::put('password_reset_token:'.$email, sha1($token));
        return $token;
    }

    public static function validateToken($email, $token)
    {
        return sha1($token) == Cache::get('password_reset_token:'.$email);
    }
}
