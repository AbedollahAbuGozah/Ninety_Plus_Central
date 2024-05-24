<?php

namespace App\Http\Controllers\Api\V1;

use App\constants\GenderOption;
use App\Models\Branch;
use App\Models\Country;
use App\Models\Role;

class GuestController extends BaseController
{
    public function getRegistrationData()
    {
        $data = [];
        $data['roles'] = Role::getAllowedRegister()->select('id', 'name')->get();
        $data['branches'] = Branch::query()->select('id', 'name')->get();
        $data['genders'] = GenderOption::options();

        foreach ($data['genders'] as $gender) {
            $role['name'] = trans('general.genders.' . $gender);
        }
        foreach ($data['roles'] as $role) {
            $role['name'] = trans('general.roles.' . $role->name);
        }
        $data['countries'] = Country::with(['cites' => function ($query) {
            $query->select('id', 'country_id', 'name');
        }])->get();

        return $this->success($data, 'messages.success.get_registration_data', 200);
    }
}
