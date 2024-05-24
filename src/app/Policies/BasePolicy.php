<?php

namespace App\Policies;

use App\Models\RolePermissionAssign;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class BasePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {

    }

    public function can(User $user)
    {
//        RolePermissionAssign::whereIn('role_id',$user->roles()->pluck('id'))->where('resource', User::ta)
    }
}
