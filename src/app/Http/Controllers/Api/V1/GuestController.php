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
        $data['genders'] = [];
        $genders = GenderOption::options();

        foreach ($genders as $index => $gender) {
            $data['genders'][] = [
                'id' => $index,
                'name' => trans('general.genders.' . $gender)
            ];
        }
        foreach ($data['roles'] as $role) {
            $role['name'] = trans('general.roles.' . $role->name);
        }
        $data['countries'] = Country::with(['cities' => function ($query) {
            $query->select('id', 'country_id', 'name');
        }])->get();

        return $this->success($data, 'messages.success.get_registration_data', 200);
    }
}
