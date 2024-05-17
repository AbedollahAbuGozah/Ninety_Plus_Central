<?php

namespace App\Http\Controllers\Api\V1;

use App\constants\Roles;
use App\Models\Country;
use App\Models\Role;

class GuestController extends BaseController
{
    public function getRegistrationData()
    {
        $data = [];
        $data['roles'] = Role::whereIn('name', [Roles::STUDENT, Roles::INSTRUCTOR])->pluck('name', 'id');
        $data['countries'] = Country::with('cites')->pluck('name', 'id');
        return $this->success($data, 'registration data retrieved successfully', 200);
    }
}
