<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
   use Notifiable, HasFactory;


    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function instructor()
    {
        return $this->hasOne(Instructor::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function isAdmin()
    {
        return $this->roles()->where('name', 'admin')->exists();
    }

    public function isHr()
    {
        return $this->roles()->where('name', 'hr')->exists();
    }

    public function isInstructor()
    {
        return $this->roles()->where('name', 'instructor')->exists();
    }


    public function isStudent()
    {
        return $this->roles()->where('name', 'student')->exists();
    }


    public function hasPermissionAccess($permission, $resourceId)
    {
        $hasPermission = false;

        foreach ($this->roles as $role) {
            $hasPermission |= (bool)RolePermissionAssign::where([
                'role_id' => $role->id,
                'resource_id' => $resourceId,
                $permission => true,
            ])->exists();
        }

        return $hasPermission;
    }

    public function generatePasswordResetCode()
    {
        return Str::random(10);
    }
}
