<?php

namespace App\Models;

use App\constants\RoleOptions;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Stripe\BankAccount;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail, HasMedia
{
    use Notifiable, HasFactory, InteractsWithMedia;


    protected $guarded = ['id'];
    protected $table = 'users';
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'properties' => 'json',
    ];

    const PROFILE_IMAGE_MEDIA_COLLECTION = 'profile_image';

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

    public function getProfileImageAttribute()
    {
        logger(__METHOD__);
        return $this->getFirstMediaUrl(User::PROFILE_IMAGE_MEDIA_COLLECTION);
    }

    public function getBalanceAttribute()
    {
        return $this->properties['balance_info']['balance'] ?? 0;
    }

    public function getWithDrawBalanceAttribute()
    {
        return $this->properties['balance_info']['with_draw_balance'] ?? 0;
    }


    public function isAdmin()
    {
        return $this->roles()->where('name', RoleOptions::ADMIN)->exists();
    }

    public function isHr()
    {
        return $this->roles()->where('name', RoleOptions::HR)->exists();
    }

    public function isInstructor()
    {
        return $this->roles()->where('name', RoleOptions::INSTRUCTOR)->exists();
    }


    public function isStudent()
    {
        return $this->roles()->where('name', RoleOptions::STUDENT)->exists();
    }

    public function student()
    {
        return Student::hydrate([$this->toArray()])->first();
    }

    public function instructor()
    {
        return Instructor::hydrate([$this->toArray()])->first();
    }

    public function invoices()
    {
        return $this->belongsToMany(Invoice::class);
    }

    public function resolveUser()
    {
        return match (true) {
            $this->isInstructor() => Instructor::hydrate([$this->toArray()])->first(),
            $this->isStudent() => Student::hydrate([$this->toArray()])->first(),
            default => $this,
        };

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

    public function bankAccount()
    {
        return $this->hasOne(BankAccount::class);
    }
}
