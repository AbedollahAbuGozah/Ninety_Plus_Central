<?php

namespace App\services;

use App\Http\Resources\PermissionRescourse;

class CurrentUserService
{
    public static function logout()
    {
        auth()->logout();
    }

    public static function get()
    {
        return auth()->user();
    }

    public function getPermissions()
    {
        $user = auth()->user()->load(['roles.permissions.resource']);

        $permissions = $user->roles->flatMap(function ($role) {
            return $role->permissions;
        })->unique('id')->groupBy(function ($perm){
            return $perm->resource->name;
        });

        $consolidatedPermissions = $permissions->map(function ($perms) {
            $permission = $perms->reduce(function ($carry, $perm) {
                if (!$carry) {
                    return $perm;
                } else {
                    $carry->view_access |= $perm->view_access;
                    $carry->modify_access |= $perm->modify_access;
                    $carry->delete_access |= $perm->delete_access;
                    $carry->add_access |= $perm->add_access;
                    $carry->manage_access |= $perm->manage_access;
                    return $carry;
                }
            });
            return PermissionRescourse::make($permission);
        });

        return $consolidatedPermissions;
    }
}


