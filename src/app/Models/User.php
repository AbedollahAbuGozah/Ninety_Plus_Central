<?php

namespace App\Models;

use App\Http\Resources\PermissionRescourse;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use Notifiable, HasFactory;


    protected $guarded = ['id'];
    protected $table = 'users';
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

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
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

    public function authorize($permission, $resource)
    {
        return $this->roles()->whereHas('permissions', function ($query) use ($resource, $permission) {
            $query->where([
                'resource_name' => $resource,
                $permission => true,
            ]);
        })->exists();
    }

    public function generatePasswordResetCode()
    {
        return Str::random(10);
    }

    public function roles()

    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id');
    }



}
