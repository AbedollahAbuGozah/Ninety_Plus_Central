<?php

namespace App\Models;

use App\constants\Roles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user');
    }

    public static function getAllowedRegister()
    {
      return  Role::whereIn('name', [Roles::INSTRUCTOR, Roles::STUDENT])->pluck('id');
    }
}
