<?php

namespace App\Models;

use App\constants\RoleOptions;
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
        return Role::where('can_register', true);
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}
