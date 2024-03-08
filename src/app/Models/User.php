<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

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
        return $this->hasMany(Role::class);
    }

    public function hasPermission($permissionAccess, $resourceId)
    { 
        $hasPermission = false;

        foreach ($this->roles as $role){    
           $hasPermission |= (bool)RolePermissionAssign::where([
                'role_id' => $role->id,
                'resource_id' => $resourceId,
                 $permissionAccess => true,
            ]);
        }
        
        return $hasPermission;
    }
}
