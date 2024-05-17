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
    protected $guarded = ['id'];
    public $timestamps = false;

    public static function generateCode()
    {
        return Str::random(10);
    }
}
