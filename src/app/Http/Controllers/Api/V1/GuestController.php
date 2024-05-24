<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Country;
use App\Models\Role;

class GuestController extends BaseController
{
    public function getRegistrationData()
    {
        $data = [];
        $data['roles'] = Role::getAllowedRegister()->pluck('name', 'id');

        $data['countries'] = Country::with(['cites' => function ($query) {
            $query->select('id', 'country_id', 'name');
        }])->get();

        return $this->success($data, 'registration data retrieved successfully', 200);
    }
}
