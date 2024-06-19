<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionRescourse;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     *
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = User::find(9)->load(['roles.permissions.resource']);

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
