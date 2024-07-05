<?php

namespace App\Policies;


class BasePolicy
{
    protected static $resource;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {

    }

    public static function check($user, $permission)
    {
        return $user->roles()
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where([
                    $permission => true,
                ])->whereHas('resource', function ($query) {
                    $query->where('name', self::$resource);
                });
            })
            ->exists();
    }


}
